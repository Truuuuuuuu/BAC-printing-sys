<x-app-layout>

    <div
    x-data="docEditor()"
    x-init="init()"
    class="min-h-screen bg-gray-50 p-6"
    
>
    <div class="max-w-6xl mx-auto flex gap-6">

        {{-- Left: argument inputs --}}
        <div class="w-72 shrink-0">
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
                        <input
                            type="text"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            :placeholder="getHint(key) || 'Enter ' + formatLabel(key)"
                            x-model="args[key]"
                            @input="schedulePreview()"
                        >
                    </div>
                </template>

                <hr class="my-4 border-gray-100">

                <button
                    @click="exportDoc()"
                    :disabled="exporting || loading || previewing"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors flex items-center justify-center gap-2"
                >
                    <svg x-show="!exporting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <svg x-show="exporting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <span x-text="exporting ? 'Exporting...' : 'Export .docx'"></span>
                </button>

                <p x-show="exportError" x-text="exportError" class="mt-2 text-xs text-red-500"></p>
            </div>
        </div>

        {{-- Right: PDF preview --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                    <div class="w-2.5 h-2.5 rounded-full bg-gray-200"></div>
                    <span class="text-sm text-gray-500 font-medium" x-text="fileName"></span>
                    <span class="ml-auto flex items-center gap-2">
                        <svg x-show="previewing" class="w-3.5 h-3.5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span class="text-xs text-gray-400" x-text="previewing ? 'Updating preview...' : 'Live preview'"></span>
                    </span>
                </div>
                <div class="relative" style="height: 80vh;">
                    <iframe
                        x-ref="previewFrame"
                        class="w-full h-full border-none"
                        style="display:block"
                    ></iframe>
                    <div
                        x-show="previewing"
                        class="absolute inset-0 bg-white bg-opacity-60 flex items-center justify-center"
                    >
                        <span class="text-sm text-gray-400">Rendering...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/mammoth.min.js') }}"></script>

<script>
    const DOC_URL    = '{{ route("doc-template") }}';
    const EXPORT_URL = '{{ route("doc-editor.export") }}';
    const PREVIEW_URL = '{{ route("doc-editor.preview") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
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

        async init() {
            try {
                const resp = await fetch(DOC_URL);
                if (!resp.ok) throw new Error('Could not load document.');
                const arrayBuffer = await resp.arrayBuffer();
                const result = await mammoth.convertToHtml({ arrayBuffer });
                this.rawHtml = result.value;

                const matches = [...this.rawHtml.matchAll(/\{\{(\w+)\}\}/g)];
                const keys = [...new Set(matches.map(m => m[1]))];
                this.placeholders = keys;
                this.args = Object.fromEntries(keys.map(k => [k, '']));
                this.placeholderKeys = keys;
            } catch (err) {
                this.loadError = err.message;
            } finally {
                this.loading = false;
                this.refreshPreview();
            }
        },

        schedulePreview() {
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
                    body: JSON.stringify({ args: this.args }),
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
                    body: JSON.stringify({ args: this.args }),
                });

                if (!resp.ok) throw new Error('Export failed');

                const blob = await resp.blob();
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'document_filled.docx';
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

        placeholderHints: {
            total_interested_bidders: 'e.g. forty-eight (48)',
            number_of_responsive_bidders: 'e.g. six (6)',
            project_title: 'e.g. Construction of Barangay Hall',
            bidders: 'e.g. GSH CONSTRUCTION, ESK CORP, ...',
            approved_budget: '400,000.00',
        },
  
    }
}
</script>
@endverbatim

</x-app-layout>