@props(['config'])

<div x-data="docEditor()" x-init="init()" class="min-h-screen bg-gray-50 p-6 text-primary">
    <div class="max-w-7xl mx-auto flex gap-6">

        {{-- ── Left: placeholder inputs ──────────────────────────────────── --}}
        <div class="w-1/4 shrink-0">
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

                        <template x-if="inputPatterns[key]">
                            <input type="text"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                :placeholder="getHint(key) || 'Enter ' + formatLabel(key)" 
                                x-model="args[key]"
                                x-mask="9999-99-999" 
                                @input="schedulePreview()">
                        </template>

                        <template x-if="!inputPatterns[key]">
                            <input type="text"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                :placeholder="getHint(key) || 'Enter ' + formatLabel(key)" x-model="args[key]"
                                @input="schedulePreview()">
                        </template>
                    </div>
                </template>

                {{-- Dynamic table sections --}}
                <div class="mt-4" x-show="Object.keys(tablesConfig).length > 0">
                    <hr class="mb-4 border-gray-100">

                    <template x-for="[group, groupCfg] in Object.entries(tablesConfig)" :key="group">
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-primary uppercase tracking-wide"
                                    x-text="groupCfg.label"></h3>
                                <button type="button" @click="addTableRow(group)"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    + Add Row
                                </button>
                            </div>

                            <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                                <template x-for="(row, rowIndex) in tableRows[group]" :key="rowIndex">
                                    <div class="border border-gray-100 rounded-lg p-2 bg-gray-50 relative">
                                        <button type="button" @click="removeTableRow(group, rowIndex)"
                                            x-show="tableRows[group].length > 1"
                                            class="absolute top-1.5 right-1.5 text-gray-300 hover:text-red-400 text-xs">
                                            ✕
                                        </button>

                                        <p class="text-xs text-primary font-semibold mb-2">
                                            Row <span x-text="rowIndex + 1"></span>
                                        </p>

                                        <template x-for="[field, fieldCfg] in Object.entries(groupCfg.fields)"
                                            :key="field">
                                            <div class="mb-1.5">
                                                <label class="block text-xs text-gray-400 mb-0.5"
                                                    x-text="fieldCfg.label"></label>
                                                <input type="text"
                                                    class="w-full text-xs border border-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-300"
                                                    :placeholder="fieldCfg.placeholder" x-model="row[field]"
                                                    @input="schedulePreview()" />
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Export button --}}
                <div class="mt-4">
                    <button @click="exportDoc()" :disabled="exporting || loading || previewing"
                        class=" w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-semibold hover:scale-105 transition-all duration-200 rounded-3xl px-4 py-2  flex items-center justify-center gap-2">
                        <x-lucide-arrow-big-down-dash class="w-4 h-4"/>
                        <svg x-show="exporting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                        </svg>
                        <span x-text="exporting ? 'Exporting...' : 'Export .docx'"></span>
                    </button>

                    <p x-show="exportError" x-text="exportError" class="mt-2 text-xs text-red-500 whitespace-pre-line">
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Right: PDF preview ─────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm sticky top-6">
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

{{-- Inject config as a single JS object. No logic here. --}}
<script>
    window.DOC_CONFIG = @json($config);
    window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>

{{-- mammoth must load before doc-editor.js --}}
<script src="{{ asset('js/mammoth.min.js') }}"></script>
<script src="{{ asset('js/doc-editor.js') }}"></script>