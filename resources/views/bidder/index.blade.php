<x-app-layout>


    <div class="py-12" x-data="{
        deleteId: null,
        editId: {{ old('edit-id', 'null') }},
        editBid: {
            company_name: '{{ old('edit-company_name') }}',
            proprietor: '{{ old('edit-proprietor') }}',
            bid_amount: '{{ old('edit-bid_amount') }}',
            street: '{{ old('edit-street') }}', 
            barangay: '{{ old('edit-barangay') }}',
            municipality_city: '{{ old('edit-municipality_city') }}',
        },
        showDeleteBidModal: false,
        showEditBidModal: {{ $errors->hasAny(['edit-company_name', 'edit-proprietor', 'edit-bid_amount', 'edit-street', 'edit-barangay', 'edit-municipality_city']) ? 'true' : 'false' }},
        showCreateBidModal: {{ $errors->hasAny(['project_title', 'company_name', 'proprietor', 'address', 'bid_amount']) ? 'true' : 'false' }},
        selectedProjectTitle: '{{ old('project_title') }}',
        selectedProjectId: '{{ old('project_id') }}',
        selectedProjectAmount: '{{ old('project_amount')}}',

        async onEditModalOpen() {
            this.$dispatch('edit-modal-opened', { 
                municipality_city: this.editBid.municipality_city,
                barangay: this.editBid.barangay
            });
        },
    }">
        <div class="max-w-[1440px] w-full mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-5">
            

            <div class="w-full min-w-0 border-2 border-white shadow-sm bg-foreground rounded-3xl ">
                <div class="flex justify-end p-5 ">

                    {{-- Search Form --}}

                    <div class="relative w-full md:max-w-md lg:max-w-xl"
                        x-data="{ search: '{{ request('bid_search') }}' }">

                        <form method="GET">
                            
                            {{-- to preserve search input in projects --}}
                            <input type="hidden" name="project_search" value="{{ request('project_search') }}">

                            <input type="text" name="bid_search" x-model="search" placeholder="Search bid records..."
                                class="w-full border px-3 py-2 pr-20 rounded-3xl border-gray-300">

                            {{-- Clear Input Search --}}
                            <button x-show="search.length > 0" x-cloak type="button" @click="
                                                search = '';
                                                window.location = '{{ route('bidder.index')}}?project_search={{ request('project_search') }}'; 
                                            "
                                class="absolute right-10 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <x-lucide-x class="w-4 h-4" />
                            </button>


                            {{-- Submit Search --}}
                            <button type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition">
                                <x-lucide-search class="w-5 h-5" />
                            </button>
                        </form>

                    </div>


                </div>
                <div class="overflow-x-auto w-full ">
                    <table class="w-full min-w-[900px]">
                        <thead>
                            <tr>
                                <th class=" px-5 text-left py-1 font-medium text-sm  opacity-60 max-w-xs">Project</th>
                                <th class="px-5 text-left py-1 font-medium text-sm  opacity-60 ">Bidder</th>
                                <th class="px-5 text-left py-1 font-medium text-sm  opacity-60 whitespace-nowrap">Proprietor</th>
                                <th class="px-5 text-left py-1 font-medium text-sm  opacity-60 whitespace-nowrap">Contract Amount</th>
                                <th class="px-5 text-left py-1 font-medium text-sm  opacity-60">Address</th>
                                <th class="px-5 text-left py-1 font-medium text-sm  opacity-60 whitespace-nowrap">Actions</th>


                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bids as $bid)

                                <tr class="border-t odd:bg-foreground even:bg-gray-100">
                                    <td class="px-5 py-1 ">
                                        <p>{{ $bid->project?->project_title }}</p>
                                        <p class="text-xs text-primary/70">
                                            ₱{{ number_format($bid->project?->approved_budget, 2) }}</p>

                                    </td>
                                    <td class="px-5 py-1 opacity-80">{{ $bid->company_name}}</td>
                                    <td class="px-5 py-1 opacity-80 whitespace-nowrap">{{ $bid->proprietor}}</td>
                                    <td class="px-5 py-1 whitespace-nowrap">₱{{ number_format($bid->bid_amount, 2)}}</td>
                                    <td class="px-5 py-1 opacity-80 capitalize">{{ $bid->full_address}}</td>
                                    <td class="px-5 py-1 whitespace-nowrap">
                                        <div class="flex gap-3 h-full items-center  justify-center ">
                                            <button 
                                                    class="hover:scale-110 transition"
                                                    @click="
                                                    editId = {{ $bid->id }};
                                                    editBid = {{ json_encode($bid) }};
                                                    showEditBidModal = true;
                                                    $nextTick(() => window.dispatchEvent(new CustomEvent('open-edit', {
                                                        detail: {
                                                            municipality_city: '{{ addslashes($bid->municipality_city) }}',
                                                            barangay: '{{ addslashes($bid->barangay) }}'
                                                        }
                                                    })));
                                                ">
                                                <x-lucide-pencil class="w-5 h-5 text-primary cursor-pointer" />
                                            </button>

                                            <button class="flex items-center hover:scale-110 transition"
                                                @click="deleteId = {{ $bid->id }}; showDeleteBidModal = true">
                                                <x-lucide-trash class="w-5 h-5 text-red-text cursor-pointer" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-5 text-center text-gray-500">
                                        No projects found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-5 p-4">
                    {{ $bids->links() }}
                </div>
            </div>
        </div>

        <x-create-bid :$cities />
        <x-delete-bid />
        <x-edit-bid :$cities />
    </div>

    {{-- auto scroll up button --}}
    <div x-data="{ visible: false }" x-init="window.addEventListener('scroll', () => {
        visible = (window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100
        })">
        <button x-show="visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4" @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-8 right-8 z-50 bg-gray-800 hover:bg-gray-600 text-white w-11 h-11 rounded-full shadow-lg text-xl cursor-pointer">
            ↑
        </button>
    </div>

    {{-- Clear localstorage in browser when bid info edited --}}
    @if(session('clear_storage'))
        <script>
            localStorage.clear();
        </script>
    @endif

</x-app-layout>