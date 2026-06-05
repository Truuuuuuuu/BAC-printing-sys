<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocEditorController extends Controller
{
    public function show()
    {
        return view('components.doc-editor');
    }

    public function export(Request $request)
    {
        $args     = $request->input('args', []);
        $template = public_path('docs/BAC Resolution Declaring LCRB.docx');
        $outPath  = storage_path('app/tmp/' . uniqid('out_') . '.docx');

        if (!file_exists($template)) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        @mkdir(storage_path('app/tmp'), 0755, true);

        $script = <<<'PYTHON'
import sys, json
from docx import Document

doc = Document(sys.argv[1])
args = json.loads(sys.argv[2])

if not isinstance(args, dict):
    args = {}

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

def replace_in_runs(runs):
    for run in runs:
        for key, val in args.items():
            placeholder = '{{' + key + '}}'
            if placeholder in run.text:
                run.text = run.text.replace(placeholder, format_value(key, val))

for para in doc.paragraphs:
    replace_in_runs(para.runs)

for table in doc.tables:
    for row in table.rows:
        for cell in row.cells:
            for para in cell.paragraphs:
                replace_in_runs(para.runs)

doc.save(sys.argv[3])
PYTHON;

        $scriptPath = storage_path('app/tmp/replace_' . uniqid() . '.py');
        file_put_contents($scriptPath, $script);

        $process = new \Symfony\Component\Process\Process([
            'python3', $scriptPath, $template, json_encode($args), $outPath
        ]);
        $process->run();

        @unlink($scriptPath);

        if (!$process->isSuccessful() || !file_exists($outPath)) {
            return response()->json(['error' => 'Export failed: ' . $process->getErrorOutput()], 500);
        }

        return response()->download($outPath, 'document_filled.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    public function preview(Request $request)
{
    $args     = $request->input('args', []);
    $template = public_path('docs/BAC Resolution Declaring LCRB.docx');
    $tmpDir   = storage_path('app/tmp/');

    @mkdir($tmpDir, 0755, true);

    $filledDocx = $tmpDir . uniqid('preview_') . '.docx';
    $filledPdf  = $filledDocx . '.pdf';

    $script = <<<'PYTHON'
import sys, json
from docx import Document

doc = Document(sys.argv[1])
args = json.loads(sys.argv[2])

if not isinstance(args, dict):
    args = {}

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

def replace_in_runs(runs):
    for run in runs:
        for key, val in args.items():
            placeholder = '{{' + key + '}}'
            if placeholder in run.text:
                run.text = run.text.replace(placeholder, format_value(key, val))

for para in doc.paragraphs:
    replace_in_runs(para.runs)

for table in doc.tables:
    for row in table.rows:
        for cell in row.cells:
            for para in cell.paragraphs:
                replace_in_runs(para.runs)

doc.save(sys.argv[3])
PYTHON;

    $scriptPath = $tmpDir . uniqid('script_') . '.py';
    file_put_contents($scriptPath, $script);

    $fill = new \Symfony\Component\Process\Process([
        '/usr/bin/python3', $scriptPath, $template, json_encode($args), $filledDocx
    ]);
    $fill->run();
    @unlink($scriptPath);

    if (!$fill->isSuccessful() || !file_exists($filledDocx)) {
    return response()->json([
        'error'  => 'Fill failed',
        'stderr' => $fill->getErrorOutput(),
        'stdout' => $fill->getOutput(),
    ], 500);
}

    $convert = new \Symfony\Component\Process\Process([
    '/opt/libreoffice26.2/program/soffice', '--headless', '--convert-to', 'pdf',
    $filledDocx, '--outdir', $tmpDir
]);
$convert->setTimeout(30);
$convert->run();
@unlink($filledDocx);

$pdfName  = pathinfo($filledDocx, PATHINFO_FILENAME) . '.pdf';
$actualPdf = $tmpDir . $pdfName;

// Only check if file exists, ignore stderr warnings from LibreOffice
if (!file_exists($actualPdf)) {
    return response()->json([
        'error'        => 'PDF conversion failed',
        'stderr'       => $convert->getErrorOutput(),
        'stdout'       => $convert->getOutput(),
        'pdf_expected' => $actualPdf,
        'tmp_files'    => scandir($tmpDir),
    ], 500);
}

    return response()->file($actualPdf, [
        'Content-Type'              => 'application/pdf',
        'Content-Disposition'       => 'inline',
        'Access-Control-Allow-Origin' => '*',
    ])->deleteFileAfterSend(true);
}
}