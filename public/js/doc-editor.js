/**
 * doc-editor.js
 *
 * Alpine component for the dynamic DOCX placeholder editor.
 * All document-specific config is injected via window.DOC_CONFIG from the blade.
 * This file never needs to change between different document templates.
 *
 * Expected window globals (set by blade):
 *   DOC_CONFIG  — full config object (see blade component for shape)
 *   CSRF_TOKEN  — Laravel CSRF token string
 */

function docEditor() {
    const cfg = window.DOC_CONFIG;

    return {
        // ── identity ─────────────────────────────────────────────────────────
        fileName:     cfg.fileName     ?? 'Document.docx',
        downloadName: cfg.downloadName ?? cfg.fileName ?? 'Document.docx',

        // ── state ─────────────────────────────────────────────────────────────
        rawHtml:      '',
        placeholders: [],          // static {{key}} keys (not row_*)
        args:         {},          // { key: value }
        tableRows:    {},          // { group: [{ field: value }] }
        tablesConfig: cfg.tablesConfig  ?? {},
        placeholderHints: cfg.hints     ?? {},

        loading:      true,
        loadError:    '',
        exporting:    false,
        exportError:  '',
        previewing:   false,
        debounceTimer: null,

        inputPatterns: cfg.inputPatterns ?? {},
        placeholderLabels: cfg.labels ?? {},

        fieldTypes: cfg.fieldTypes ?? {},
        

        // ── init ──────────────────────────────────────────────────────────────
        async init() {
            try {
                const resp = await fetch(cfg.docUrl);
                if (!resp.ok) throw new Error('Could not load document.');

                const arrayBuffer = await resp.arrayBuffer();
                const result = await mammoth.convertToHtml({ arrayBuffer });
                this.rawHtml = result.value;

                // Collect all {{key}} placeholders from the HTML
                const allKeys = [...new Set(
                    [...this.rawHtml.matchAll(/\{\{(\w+)\}\}/g)].map(m => m[1])
                )];

                // Separate static fields from table row fields (row_*)
                this.placeholders = allKeys.filter(k => !k.startsWith('row_'));

                // Build empty args from discovered static keys
                this.args = Object.fromEntries(this.placeholders.map(k => [k, '']));

                // Build empty table rows (one blank row per group)
                this.tableRows = {};
                for (const [group, groupCfg] of Object.entries(this.tablesConfig)) {
                    this.tableRows[group] = [
                        Object.fromEntries(Object.keys(groupCfg.fields).map(f => [f, '']))
                    ];
                }

                // Restore user input from localStorage (user edits take priority)
                this.loadFromStorage();

                // Config defaults always win over blank, but NOT over user-saved data.
                // If you want config defaults to ALWAYS override storage, swap the order:
                //   this.args = { ...this.args, ...(cfg.defaults ?? {}) };
                // Current behaviour: storage wins if a value was previously saved.
                const defaults = cfg.defaults ?? {};
                for (const [k, v] of Object.entries(defaults)) {
                    if (!this.args[k]) this.args[k] = v;
                }

                const defaultRows = cfg.defaultRows ?? {};
                for (const [group, rows] of Object.entries(defaultRows)) {
                    if (this.tableRows[group] && this.tableRows[group][0]) {
                        for (const [field, value] of Object.entries(rows[0])) {
                            if (!this.tableRows[group][0][field]) {
                                this.tableRows[group][0][field] = value;
                            }
                        }
                    }
                }

            } catch (err) {
                this.loadError = err.message;
            } finally {
                this.loading = false;
                this.refreshPreview();
            }
        },

        // ── preview ───────────────────────────────────────────────────────────
        schedulePreview() {
            this.saveToStorage();
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => this.refreshPreview(), 600);
        },

        async refreshPreview() {
            this.previewing = true;
            try {
                const resp = await fetch(cfg.previewUrl, {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.CSRF_TOKEN,
                    },
                    body: JSON.stringify({ args: this.args, table_rows: this.tableRows }),
                });
                if (!resp.ok) throw new Error('Preview failed');

                const blob = await resp.blob();
                const url  = URL.createObjectURL(blob);
                const old  = this.$refs.previewFrame.src;
                this.$refs.previewFrame.src = url;
                if (old && old.startsWith('blob:')) URL.revokeObjectURL(old);
            } catch (err) {
                console.error('Preview error:', err);
            } finally {
                this.previewing = false;
            }
        },

        // ── export ────────────────────────────────────────────────────────────
        async exportDoc() {
            this.exporting    = true;
            this.exportError  = '';
            try {
                const resp = await fetch(cfg.exportUrl, {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.CSRF_TOKEN,
                    },
                    body: JSON.stringify({ args: this.args, table_rows: this.tableRows }),
                });

                if (resp.status === 422) {
                    const data = await resp.json();
                    this.exportError = (data.errors ?? []).join('\n');
                    return;
                }
                if (!resp.ok) throw new Error('Export failed');

                const blob = await resp.blob();
                const url  = URL.createObjectURL(blob);
                const a    = document.createElement('a');
                a.href     = url;
                a.download = this.downloadName;
                a.click();
                URL.revokeObjectURL(url);
            } catch (err) {
                this.exportError = 'Export failed. Please try again.';
            } finally {
                this.exporting = false;
            }
        },

        // ── table helpers ─────────────────────────────────────────────────────
        addTableRow(group) {
            const empty = Object.fromEntries(
                Object.keys(this.tablesConfig[group].fields).map(f => [f, ''])
            );
            this.tableRows[group].push(empty);
            this.schedulePreview();
        },

        removeTableRow(group, index) {
            this.tableRows[group].splice(index, 1);
            this.schedulePreview();
        },

        getGroupFields(group) {
            return Object.entries(this.tablesConfig[group]?.fields ?? {});
        },

        // ── label / hint helpers ──────────────────────────────────────────────
        formatLabel(key) {
            const customLabels = cfg.labels ?? {};
            if (customLabels[key]) return customLabels[key];
            return key
                .replace(/_upper$/,      '')
                .replace(/_lower$/,      '')
                .replace(/_wordNumLower$/,    '')
                .replace(/_wordNumUpper$/,    '')
                .replace(/_date$/,    '')
                .replace(/_dateTime$/,    '')
                .replace(/__dateMonthDay$/,    '')
                .replace(/_numWord$/,    '')
                .replace(/_capitalize$/, '')
                .replace(/_/g, ' ')
                .replace(/\b\w/g, c => c.toUpperCase());
        },

        getHint(key) {
            const base = key
                .replace(/_upper$/,      '')
                .replace(/_lower$/,      '')
                .replace(/_wordNumLower$/,    '')
                .replace(/_wordNumUpper$/,    '')
                .replace(/_numWord$/,    '')
                .replace(/_date$/,    '')
                .replace(/_dateTime$/,    '')
                .replace(/__dateMonthDay$/,    '')
                .replace(/_capitalize$/, '');
            return this.placeholderHints[base] ?? '';
        },

        // ── storage ───────────────────────────────────────────────────────────
        storageKey() {
            // Scope localStorage to this specific document so two editors don't clash.
            return cfg.storageKey ?? `doc_editor_${cfg.docUrl}`;
        },

        saveToStorage() {
            try {
                localStorage.setItem(this.storageKey() + '_args',   JSON.stringify(this.args));
                localStorage.setItem(this.storageKey() + '_tables', JSON.stringify(this.tableRows));
            } catch (e) {
                console.warn('Could not save to storage', e);
            }
        },

        loadFromStorage() {
            try {
                const savedArgs   = localStorage.getItem(this.storageKey() + '_args');
                const savedTables = localStorage.getItem(this.storageKey() + '_tables');
                if (savedArgs)   this.args      = { ...this.args,      ...JSON.parse(savedArgs) };
                if (savedTables) this.tableRows = { ...this.tableRows, ...JSON.parse(savedTables) };
            } catch (e) {
                console.warn('Could not restore from storage', e);
            }
        },

        
    };
}