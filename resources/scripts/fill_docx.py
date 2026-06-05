import sys
import json
import copy
import re
from docx import Document
from docx.oxml.ns import qn

doc = Document(sys.argv[1])
args = json.loads(sys.argv[2])
table_rows = json.loads(sys.argv[3]) if len(sys.argv) > 4 else []

if not isinstance(args, dict):
    args = {}
if not isinstance(table_rows, list):
    table_rows = []

def format_value(key, val):
    if val is None:
        return ''
    val = str(val)
    if key.endswith('_upper'):
        return val.upper()
    if key.endswith('_lower'):
        return val.lower()
    if key.endswith('_capitalize'):
        return val.capitalize()
    return val

def merge_and_replace_paragraph(para, extra=None):
    """Only merges runs if a split placeholder is detected."""
    if not para.runs:
        return
    context = {**args, **(extra or {})}
    full_text = ''.join(run.text for run in para.runs)

    # Check if any placeholder exists in the merged text
    has_placeholder = any(('{{' + key + '}}') in full_text for key in context)
    if not has_placeholder:
        return  # Nothing to replace, don't touch formatting

    # Check if all placeholders are already whole in individual runs
    all_intact = True
    for key in context:
        placeholder = '{{' + key + '}}'
        if placeholder in full_text:
            # Check if it exists intact in any single run
            if not any(placeholder in run.text for run in para.runs):
                all_intact = False
                break

    if all_intact:
        # Safe to replace run by run — preserves formatting per run
        for run in para.runs:
            for key, val in context.items():
                placeholder = '{{' + key + '}}'
                if placeholder in run.text:
                    run.text = run.text.replace(placeholder, format_value(key, val))
    else:
        # Placeholder is split — must merge, but copy first run's XML properties
        full_text_replaced = full_text
        for key, val in context.items():
            placeholder = '{{' + key + '}}'
            if placeholder in full_text_replaced:
                full_text_replaced = full_text_replaced.replace(placeholder, format_value(key, val))
        para.runs[0].text = full_text_replaced
        for run in para.runs[1:]:
            run.text = ''

def is_template_row(row):
    for cell in row.cells:
        for para in cell.paragraphs:
            full_text = ''.join(run.text for run in para.runs)
            if '{{row_' in full_text:
                return True
    return False

# Static body paragraphs
for para in doc.paragraphs:
    merge_and_replace_paragraph(para)

# Tables
for table in doc.tables:
    template_row_idx = None
    for i, row in enumerate(table.rows):
        if is_template_row(row):
            template_row_idx = i
            break

    if template_row_idx is None:
        for row in table.rows:
            for cell in row.cells:
                for para in cell.paragraphs:
                    merge_and_replace_paragraph(para)
        continue

    template_tr = table.rows[template_row_idx]._tr
    new_trs = []

    for row_data in table_rows:
        new_tr = copy.deepcopy(template_tr)
        for cell_elem in new_tr.findall('.//' + qn('w:tc')):
            for para_elem in cell_elem.findall('.//' + qn('w:p')):
                runs = para_elem.findall('.//' + qn('w:r'))
                if not runs:
                    continue
                # Merge all run texts
                full_text = ''.join(
                    (r.find(qn('w:t')).text or '')
                    if r.find(qn('w:t')) is not None else ''
                    for r in runs
                )
                # Replace placeholders
                context = {**args, **row_data}
                for key, val in context.items():
                    placeholder = '{{' + key + '}}'
                    if placeholder in full_text:
                        full_text = full_text.replace(placeholder, format_value(key, val))
                # Write back to first run, clear rest
                first_t = runs[0].find(qn('w:t'))
                if first_t is not None:
                    first_t.text = full_text
                    first_t.set('{http://schemas.openxmlformats.org/wordprocessingml/2006/main}space', 'preserve')
                for run in runs[1:]:
                    t = run.find(qn('w:t'))
                    if t is not None:
                        t.text = ''
        new_trs.append(new_tr)

    parent = template_tr.getparent()
    insert_idx = list(parent).index(template_tr)
    parent.remove(template_tr)
    for offset, new_tr in enumerate(new_trs):
        parent.insert(insert_idx + offset, new_tr)

doc.save(sys.argv[4])