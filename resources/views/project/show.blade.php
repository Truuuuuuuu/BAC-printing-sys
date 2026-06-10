<x-app-layout>

    <div class="py-12" x-data="{
        deleteId: null,
        editId: {{ old('edit-id', 'null') }},
        editBid: {
            company_name: '{{ old('edit-company_name') }}',
            proprietor: '{{ old('edit-proprietor') }}',
            bid_amount: '{{ old('edit-bid_amount') }}',
            address: '{{ old('edit-address') }}',
        },
        bidId: null,

        showDeleteBidModal: false,
        showEditBidModal: {{ $errors->hasAny(['edit-company_name', 'edit-proprietor', 'edit-address', 'edit-bid_amount']) ? 'true' : 'false' }},
        showCreateBidModal: {{ $errors->hasAny(['project_title', 'company_name', 'proprietor', 'address', 'bid_amount']) ? 'true' : 'false' }},
        showAwardModal: false,
        
        selectedProjectTitle: '{{ old('project_title') }}',
        selectedProjectId: '{{ old('project_id') }}',
        selectedProjectAmount: '{{ old('project_amount')}}',

    }">
        <div class="max-w-[1440px] w-full mx-auto sm:px-6 lg:px-8  text-primary space-y-5">
            <button type="button" onclick="history.back()" class="inline-flex gap-2 items-center">
                <x-lucide-chevron-left class="w-5 h-5 text-primary" />
                Back
            </button>
            <div class=" w-full rounded-3xl p-4 pt-8 border border-gray-300 shadow-sm bg-foreground">
                <div class="mb-2">
                    <p class="text-xs text-primary/70">Project Title</p>
                    <h1 class="font-semibold text-xl">{{ $project->project_title }}</h1>
                </div>

                <div class="mt-5">
                    <div class="w-full bg-gray-300 h-[1px] "></div>
                </div>

                <div class="grid grid-cols-3 py-3">
                    <div>
                        <p class="text-xs text-primary/70">Approved Budget</p>
                        <h1 class="font-semibold text-lg">₱{{ number_format($project->amount, 2) }}</h1>
                    </div>



                    <div class=" border-l-2 border-r-2 pl-3">
                        <p class="text-xs text-primary/70">Bidding Date</p>
                        <h1 class="font-semibold text-lg">{{ $project->bidding_date->format('M d, Y') }}</h1>
                    </div>



                    <div class="pl-3">
                        <p class="text-xs text-primary/70">Status</p>
                        <div
                            class="rounded-xl capitalize {{ $project->status === 'awarded' ? ' text-green-text/70' : ' text-red-text/70' }} font-semibold text-lg ">
                            {{ $project->status }}
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="w-full bg-gray-300 h-[1px] "></div>
                </div>
                @if($project->bids()->exists())
                    <div class="flex flex-wrap justify-start gap-3">
                        <div>
                            {{-- Edit/Print Resolution Declaring LCRB--}}
                            <a href="{{ route('doc.editor-show', [$project, 'bac-resolution']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>BAC Resolution Declarating LCRB</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/Print Bid Evluation Report--}}
                            <a href="{{ route('doc.editor-show', [$project, 'evaluation-report']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>Bid Evaluation Report</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/print NGPA_Contract-Form--}}
                            <a href="{{ route('doc.editor-show', [$project, 'contract-form']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>NGPA Contract-Form</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/print Notice of Award--}}
                            <a href="{{ route('doc.editor-show', [$project, 'award-notice']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>Notice of Award</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/Print Notice Post-Qualification--}}
                            <a href="{{ route('doc.editor-show', [$project, 'notice-post-qualification']) }}"
                                target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>Notice of Post-Qualification</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/Print Notice to Proceed--}}
                            <a href="{{ route('doc.editor-show', [$project, 'notice-proceed']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>Notice to Proceed</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/Print Notification of Lowest Calculated Bid--}}
                            <a href="{{ route('doc.editor-show', [$project, 'notif-lcb']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>Notification of Lowest Calculation Bid</span>
                            </a>
                        </div>

                        <div>
                            {{-- Edit/Print Post-Qualification Evaluation Report--}}
                            <a href="{{ route('doc.editor-show', [$project, 'post-quali-eval']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                    hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>Post Qualification Evaluation Report</span>
                            </a>
                        </div>
                    </div>
                @endif

            </div>



            <div class="table-responsive w-full rounded-3xl border border-gray-300 shadow-md bg-foreground pb-5">
                <div class="flex justify-between bg-foreground items-center my-5 px-5">
                    <div>
                        <h3 class="text-xl font-semibold text-primary">Competitive Bidders</h3>
                        <h5 class="mb-0 -mt-1 text-sm">A total of <strong>{{ $bids->count() }}</strong> Bidders
                            participating</h5>
                    </div>

                    <button
                        class=" px-5 flex items-center justify-center gap-2 bg-bg-green hover:bg-primary hover:scale-105 text-foreground font-semibold rounded-xl py-2 hover:shadow-md transition-all duration-200"
                        @click="showCreateBidModal = true; selectedProjectTitle='{{ $project->project_title }}'; selectedProjectId='{{ $project->id }}'; selectedProjectAmount='{{ $project->amount }}'">
                        <x-heroicon-s-plus-circle class="w-6 h-6 text-foreground" />
                        <span class="block text-center text-lg">
                            New Bid
                        </span>
                    </button>
                </div>
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="text-primary bg-gray-200 [&>th]:py-3">
                            <th class="px-2 py-1 text-left font-bold">#</th>
                            <th class="px-2 py-1 text-left font-bold">Bidder</th>
                            <th class="px-2 py-1 text-left font-bold">Proprietor</th>
                            <th class="px-2 py-1 text-left font-bold">Contract Amount</th>
                            <th class="px-2 py-1 text-left font-bold">Address</th>
                            <th class="px-2 py-1 text-center font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bids as $index => $bid)
                            <tr
                                class=" {{ $project->awardedBid?->id == $bid->id ? 'text-green-text bg-bg-green/20 hover:bg-bg-green/50' : 'odd:bg-white  even:bg-gray-100  ' }} [&>td]:py-4  transition">
                                <td
                                    class="px-2 py-1 text-center {{ $project->awardedBid?->id == $bid->id ? 'border-l-4 border-l-bg-green' : '' }}">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-2 py-1 font-bold">{{ $bid->company_name }}</td>
                                <td class="px-2 py-1">{{ $bid->proprietor }}</td>
                                <td class="px-2 py-1 font-bold">
                                    ₱{{ number_format($bid->bid_amount, 2) }}</td>
                                <td class="px-2 py-1">{{ $bid->address }}</td>
                                <td class="px-2 py-1 whitespace-nowrap">
                                    <div class="flex gap-3 h-full items-center  justify-center ">

                                        <button title="Award" class="flex items-center hover:scale-110 transition" @click=" bidId={{ $bid->id }};
                                                                                                                        showAwardModal = true;
                                                                                                                    ">
                                            @if($bid->project->awardedBid->id == $bid->id)
                                                <x-heroicon-s-check-badge class="w-6 h-6 text-bg-green" />
                                            @else
                                                <x-heroicon-o-check-badge class="w-6 h-6 text-primary" />
                                            @endif
                                        </button>

                                        <button class="flex items-center hover:scale-110 transition" title="Edit"
                                            @click="editId = {{ $bid->id }}; editBid = {{ json_encode($bid) }}; showEditBidModal = true">
                                            <x-lucide-pencil class="w-5 h-5 text-primary cursor-pointer" />
                                        </button>

                                        <button class="flex items-center hover:scale-110 transition" title="Delete"
                                            @click="deleteId = {{ $bid->id }}; showDeleteBidModal = true">
                                            <x-lucide-trash class="w-5 h-5 text-red-text cursor-pointer" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted ">

                                    <p class="mb-0">No bids have been submitted for this project yet.</p>

                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
                <div class="px-3 mt-5">
                    {{ $bids->links() }}
                </div>

            </div>

            <br>

        </div>

        <x-create-bid />
        <x-edit-bid />
        <x-delete-bid />
        <x-award-bid />
    </div>

    {{-- Clear localstorage in browser every new bid awarded --}}
    @if(session('clear_storage'))
        <script>
            localStorage.clear();
        </script>
    @endif
</x-app-layout>