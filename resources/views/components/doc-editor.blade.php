@props(['defaults'])

<div x-data="docEditor()" x-init="init()" class="min-h-screen bg-gray-50 p-6 text-primary">
    <div class="max-w-7xl mx-auto flex gap-6">

        {{-- Left: argument inputs --}}
        <div class="w-1/4  shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sticky top-6">
                <div class="mb-4">
                    <h2 class="font-semibold text-gray-800 text-sm">Fill placeholders</h2>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="fileName"></p>
                </div>

                <div x-show="loading" class="text-sm text-gray-400 italic">Loading document...</div>
                <div x-show="loadError" x-text="loadError" class="text-sm text-red-500"></div>

                <template x-if="!loading && placeholders.length === 0">
                    <p class="text-sm text-gray-400 italic">No placeholders found in document.</p>
                </template>

                <template x-for="key in placeholders" :key="key">
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-500 mb-1" x-text="formatLabel(key)"></label>
                        <input type="text"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            :placeholder="getHint(key) || 'Enter ' + formatLabel(key)" x-model="args[key]"
                            @input="schedulePreview()">
                    </div>
                </template>

                <hr class="my-4 border-gray-100">

                {{-- Dynamic table rows --}}
                <div class="mt-4" x-show="Object.keys(tablesConfig).length > 0">

                    <template x-for="[group, config] in Object.entries(tablesConfig)" :key="group">
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-primary uppercase tracking-wide"
                                    x-text="config.label"></h3>
                                <button type="button" @click="addTableRow(group)"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">+ Add Row</button>
                            </div>

                            <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                                <template x-for="(row, rowIndex) in tableRows[group]" :key="rowIndex">
                                    <div class="border border-gray-100 rounded-lg p-2 bg-gray-50 relative">
                                        <button type="button" @click="removeTableRow(group, rowIndex)"
                                            x-show="tableRows[group].length > 1"
                                            class="absolute top-1.5 right-1.5 text-gray-300 hover:text-red-400 text-xs">✕</button>

                                        <p class="text-xs text-primary font-semibold mb-2">
                                            Row <span x-text="rowIndex + 1"></span>
                                        </p>

                                        <template x-for="[field, cfg] in Object.entries(config.fields)" :key="field">
                                            <div class="mb-1.5">
                                                <label class="block text-xs text-gray-400 mb-0.5"
                                                    x-text="cfg.label"></label>
                                                <input type="text"
                                                    class="w-full text-xs border border-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-300"
                                                    :placeholder="cfg.placeholder" x-model="row[field]"
                                                    @input="schedulePreview()" />
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>

                <button @click="exportDoc()" :disabled="exporting || loading || previewing"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors flex items-center justify-center gap-2">
                    <svg x-show="!exporting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <svg x-show="exporting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                    </svg>
                    <span x-text="exporting ? 'Exporting...' : 'Export .docx'"></span>
                </button>

                <p x-show="exportError" 
                    x-text="exportError" 
                    class="mt-2 text-xs text-red-500 whitespace-pre-line"></p>
            </div>
        </div>

        {{-- Right: PDF preview --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm sticky top-6">

                {{-- Header --}}
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                    <span class="text-sm text-gray-500 font-medium" x-text="fileName"></span>
                    <span class="ml-auto flex items-center gap-2">
                        <svg x-show="previewing" class="w-3.5 h-3.5 animate-spin text-blue-500" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="green" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                        </svg>
                        <span class="text-xs text-green-500"
                            x-text="previewing ? 'Updating preview...' : 'Live preview'"></span>
                    </span>
                </div>

                {{-- Preview frame --}}
                <div class="relative overflow-hidden rounded-b-xl" style="height: calc(100vh - 3rem);">
                    <iframe x-ref="previewFrame" class="w-full h-full border-none" style="display:block"></iframe>
                    <div x-show="previewing" class="absolute inset-0 bg-white/60 flex items-center justify-center">
                        <span class="text-sm text-gray-400">Rendering...</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/mammoth.min.js') }}"></script>

<script>
    const DOC_URL = '{{ route("doc-template") }}';
    const EXPORT_URL = '{{ route("doc-editor.export") }}';
    const PREVIEW_URL = '{{ route("doc-editor.preview") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const DEFAULT_ARGS = @json($defaults);
</script>

@verbatim
    <script>
        function docEditor() {

            return {
                fileName: 'BAC Resolution Declaring LCRB.docx',
                rawHtml: '',
                placeholders: [],
                args: {},
                loading: true,
                loadError: '',
                exporting: false,
                exportError: '',
                previewing: false,
                debounceTimer: null,



                placeholderHints: {
                    total_interested_bidders: 'e.g. forty-eight (48)',
                    number_of_responsive_bidders: 'e.g. six (6)',
                    project_title: 'e.g. Construction of Barangay Hall',
                    bidders: 'e.g. GSH CONSTRUCTION, ESK CORP, ...',
                    approved_budget: 'e.g. 400,000.00',
                    resolution_number: '0000-00-000',
                    winning_bidder: 'e.g. ABC CONSTRUCTION',
                    philGEPS_posting_date: 'MM/DD/YY',
                    conspicuous_place_posting_date: 'MM/DD/YY-MM/DD/YY',

                },

                tablesConfig: {
                    a: {
                        label: 'Table 1 — Bid Amount (As Read)',
                        fields: {
                            row_a_bidder_upper: { label: 'Name of Bidder', placeholder: 'e.g. ABC CONSTRUCTION' },
                            row_a_amount: { label: 'Bid Amount (As Read)', placeholder: 'e.g. 100,000.00' },
                            row_a_variance: { label: '% Variance from ABC', placeholder: 'e.g. 2.5%' },
                        }
                    },
                    b: {
                        label: 'Table 2 — Bid Amount (As Calculated)',
                        fields: {
                            row_b_bidder_upper: { label: 'Name of Bidder', placeholder: 'e.g. ABC CONSTRUCTION' },
                            row_b_amount: { label: 'Bid Amount (As Calculated)', placeholder: 'e.g. 100,000.00' },
                            row_b_variance: { label: '% Variance from ABC', placeholder: 'e.g. 2.5%' },
                        }
                    },
                },

                tableRows: {},

               async init() {
                try {
                    const resp = await fetch(DOC_URL);
                    if (!resp.ok) throw new Error('Could not load document.');
                    const arrayBuffer = await resp.arrayBuffer();
                    const result = await mammoth.convertToHtml({ arrayBuffer });
                    this.rawHtml = result.value;

                    const keys = [...new Set(
                        [...this.rawHtml.matchAll(/\{\{(\w+)\}\}/g)].map(m => m[1])
                    )];

                    const staticFields = keys.filter(k => !k.startsWith('row_'));
                    this.placeholders = staticFields;

                    // 1. Build empty args
                    this.args = Object.fromEntries(staticFields.map(k => [k, '']));

                    // 2. Build empty table rows
                    this.tableRows = {};
                    for (const [group, config] of Object.entries(this.tablesConfig)) {
                        this.tableRows[group] = [
                            Object.fromEntries(Object.keys(config.fields).map(f => [f, '']))
                        ];
                    }

                    // 3. Restore saved user input
                    this.loadFromStorage();

                    // 4. DEFAULT_ARGS always wins (project model values never overridden)
                    this.args = { ...this.args, ...DEFAULT_ARGS };

                } catch (err) {
                    this.loadError = err.message;
                } finally {
                    this.loading = false;
                    this.refreshPreview();
                }
            },

                schedulePreview() {
                    this.saveToStorage(); 
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => this.refreshPreview(), 600);
                },

                async refreshPreview() {
                    this.previewing = true;
                    try {
                        const resp = await fetch(PREVIEW_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({ args: this.args, table_rows: this.tableRows }),
                        });

                        if (!resp.ok) throw new Error('Preview failed');

                        const blob = await resp.blob();
                        const url = URL.createObjectURL(blob);
                        const old = this.$refs.previewFrame.src;
                        this.$refs.previewFrame.src = url;
                        if (old.startsWith('blob:')) URL.revokeObjectURL(old);
                    } catch (err) {
                        console.error('Preview error:', err);
                    } finally {
                        this.previewing = false;
                    }
                },

                async exportDoc() {
                    this.exporting = true;
                    this.exportError = '';

                    try {
                        const resp = await fetch(EXPORT_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({ args: this.args, table_rows: this.tableRows }),
                        });

                        if (resp.status === 422) {
                            const data = await resp.json();
                            this.exportError = data.errors.join('\n');
                            return;
                        }

                        if (!resp.ok) throw new Error('Export failed');

                        const blob = await resp.blob();
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'BAC Resolution Declaring LCRB.docx';
                        a.click();
                        URL.revokeObjectURL(url);
                    } catch (err) {
                        this.exportError = 'Export failed. Please try again.';
                    } finally {
                        this.exporting = false;
                    }
                },

                formatLabel(key) {
                    return key
                        .replace(/_upper$/, '')
                        .replace(/_lower$/, '')
                        .replace(/_capitalize$/, '')
                        .replace(/_/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase());
                },

                getHint(key) {
                    const baseKey = key
                        .replace(/_upper$/, '')
                        .replace(/_lower$/, '')
                        .replace(/capitalize$/, '');
                    return this.placeholderHints[baseKey] || '';
                },



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

                getRowFieldConfig(field) {
                    return this.rowFieldConfig[field] || {
                        label: this.formatLabel(field),
                        placeholder: 'Enter ' + this.formatLabel(field),
                    };
                },

                saveToStorage() {
                    localStorage.setItem('doc_args', JSON.stringify(this.args));
                    localStorage.setItem('doc_table_rows', JSON.stringify(this.tableRows));
                },

                loadFromStorage() {
                    try {
                        const savedArgs = localStorage.getItem('doc_args');
                        const savedRows = localStorage.getItem('doc_table_rows');
                        if (savedArgs) this.args = { ...this.args, ...JSON.parse(savedArgs) };
                        if (savedRows) this.tableRows = { ...this.tableRows, ...JSON.parse(savedRows) };
                    } catch (e) {
                        console.warn('Could not restore from storage', e);
                    }
                },
            }
        }
    </script>
@endverbatim