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
            <div class="mb-2">
                <p class="text-xs text-primary/70">Project Title</p>
                <h1 class="font-semibold text-xl">{{ $project->project_title }}</h1>
            </div>

            <div class="flex justify-between">
                <div>
                    <p class="text-xs text-primary/70">Approved Budget</p>
                    <h1 class="font-semibold text-lg">₱{{ number_format($project->amount, 2) }}</h1>
                </div>

                <div>
                    <p class="text-xs text-primary/70">Bidding Date</p>
                    <h1 class="font-semibold text-lg">{{ $project->bidding_date->format('M d, Y') }}</h1>
                </div>

                <div>
                    <p class="text-xs text-primary/70">Status</p>
                    <div
                        class="rounded-xl capitalize {{ $project->status === 'awarded' ? ' text-green-text/70' : ' text-red-text/70' }} font-semibold text-lg flex justify-center items-center ">
                        {{ $project->status }}
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-start gap-3">
                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="{{ route('doc.editor-show', [$project, 'bac-resolution']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>BAC Resolution Declarating LCRB</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="{{ route('doc.editor-show', [$project, 'evaluation-report']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>Bid Evaluation Report</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>NGPA Contract-Form</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>Notice of Award</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>Notice of Post-Qualification</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>Notice to Proceed</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>Notification of Lowest Calculation Bid</span>
                    </a>
                </div>

                <div>
                    {{-- Print Resolution Declaring LCRB--}}
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                        <x-lucide-printer class="w-5 h-5 text-foreground" />
                        <span>Post Qualification Evaluation Report</span>
                    </a>
                </div>
            </div>


            <div class="bg-foreground rounded-3xl p-4">

                <div class="flex justify-between items-center my-3">
                    <h5 class="mb-0">A total of <strong>{{ $bids->count() }}</strong> Bidders</h5>

                    <button
                        class="relative w-full max-w-[250px] bg-bg-green hover:bg-primary hover:scale-105 text-foreground font-semibold rounded-3xl py-1 hover:shadow-md transition-all duration-200"
                        @click="showCreateBidModal = true; selectedProjectTitle='{{ $project->project_title }}'; selectedProjectId='{{ $project->id }}'; selectedProjectAmount='{{ $project->amount }}'">

                        <span class="block text-center">
                            New Bid
                        </span>

                        <x-lucide-badge-plus class="w-6 h-6 absolute right-1 top-1/2 -translate-y-1/2" />
                    </button>
                </div>

                <div class="card-body p-0 w-">
                    @if ($bids->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <p class="mb-0">No bids have been submitted for this project yet.</p>
                        </div>
                    @else
                        <div class="table-responsive w-full">
                            <table class="w-full border-collapse text-sm">
                                <thead>
                                    <tr class="text-primary">
                                        <th class="border border-gray-50 px-2 py-1 text-center font-bold">#</th>
                                        <th class="border border-gray-50 px-2 py-1 text-center font-bold">Bidder</th>
                                        <th class="border border-gray-50 px-2 py-1 text-center font-bold">Proprietor</th>
                                        <th class="border border-gray-50 px-2 py-1 text-center font-bold">Contract Amount</th>
                                        <th class="border border-gray-50 px-2 py-1 text-center font-bold">Address</th>
                                        <th class="border border-gray-50 px-2 py-1 text-center font-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bids as $index => $bid)
                                        <tr class=" {{ $project->awardedBid?->id == $bid->id ? 'text-green-text font-bold bg-bg-green/20 hover:bg-bg-green/50' : 'odd:bg-white  even:bg-gray-200 hover:bg-gray-100 ' }}  transition">
                                            <td class="border border-gray-50 px-2 py-1 text-center">{{ $index + 1 }}</td>
                                            <td class="border border-gray-50 px-2 py-1">{{ $bid->company_name }}</td>
                                            <td class="border border-gray-50 px-2 py-1">{{ $bid->proprietor }}</td>
                                            <td class="border border-gray-50 px-2 py-1">
                                                ₱{{ number_format($bid->bid_amount, 2) }}</td>
                                            <td class="border border-gray-50 px-2 py-1">{{ $bid->address }}</td>
                                            <td class="border border-gray-50 px-2 py-1 whitespace-nowrap">
                                                <div class="flex gap-3 h-full items-center  justify-center ">

                                                    <button title="Award" class="flex items-center hover:scale-110 transition"
                                                        @click=" bidId={{ $bid->id }};
                                                        showAwardModal = true;
                                                    ">
                                                        <x-lucide-badge-check class="w-5 h-5 text-bg-green" />
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <br>

                {{ $bids->links() }}
            </div>
        </div>

        <x-create-bid />
        <x-edit-bid />
        <x-delete-bid />
        <x-award-bid/>
    </div>
</x-app-layout>