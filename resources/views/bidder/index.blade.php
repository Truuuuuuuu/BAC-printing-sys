<x-app-layout>


    <div class="py-12" x-data="{
        deleteId: null,
        showDeleteBidModal: false,
        showCreateBidModal: {{ $errors->hasAny(['project_title', 'company_name', 'proprietor', 'address', 'bid_amount']) ? 'true' : 'false' }},
        selectedProjectTitle: '{{ old('project_title') }}',
        selectedProjectId: '{{ old('project_id') }}',
        selectedProjectAmount: '{{ old('project_amount')}}',
    }">
        <div class="max-w-[1440px] mx-auto sm:px-6 lg:px-8 flex gap-5">
            <div class="w-[390px]  shrink-0 self-start sticky top-6 space-y-5">
                <div class="flex gap-2 items-center">
                    <div class="border rounded-2xl bg-foreground flex items-center justify-center p-4">
                        <x-lucide-folder-open class="w-8 h-8 text-primary" />
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-3xl text-primary font-semibold">Projects</h2>
                        <p class="text-xs text-primary/80">Browse and select available projects to create a bid.
                        </p>
                    </div>
                </div>


                <div>
                    <div class="relative w-full" x-data="{ search: '{{ request('search') }}' }">

                        <form method="GET">
                            <input type="text" name="search" x-model="search" placeholder="Search projects..."
                                class="w-full border p-2 pr-20 rounded-xl">

                            {{-- Clear Input Search --}}
                            <button x-show="search.length > 0" x-cloak type="button" @click="
                                            search = '';
                                            window.location = '{{ route('bidder.index') }}';
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

                <div class="space-y-2">
                    @forelse ($projects as $project)

                        <x-project-card :project="$project" />

                    @empty

                        <p class="text-gray-500 text-center">No projects found.</p>

                    @endforelse
                </div>

            </div>

            <div class="w-full border bg-foreground rounded-2xl p-5">
                <div class="flex justify-end ">
                    <form method="GET" class="w-full">
                        {{-- Search Form --}}
                        <div class="flex justify-end">
                            <div class="relative w-2/3" x-data="{ search: '{{ request('search') }}' }">

                                <form method="GET">
                                    <input type="text" name="search" x-model="search" placeholder="Search projects..."
                                        class="w-full border p-2 pr-20 rounded-xl">

                                    {{-- Clear Input Search --}}
                                    <button x-show="search.length > 0" x-cloak type="button" @click="
                                            search = '';
                                            window.location = '{{ route('bidder.index') }}';
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
                    </form>
                </div>
                <table class="w-full mt-10">
                    <thead>
                        <tr>
                            <th class="text-left p-2 max-w-xs">Project Title</th>
                            <th class="text-left p-2 ">Approved Budget</th>
                            <th class="text-left p-2 whitespace-nowrap">Bidding Date</th>
                            <th class="text-left p-2 ">Bidder</th>
                            <th class="text-left p-2 whitespace-nowrap">Proprietor</th>
                            <th class="text-left p-2 whitespace-nowrap">Contract Amount</th>
                            <th class="text-left p-2 ">Address</th>
                            <th class="text-left p-2 whitespace-nowrap">Actions</th>


                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)

                            <tr class="border-t">
                                <td class="p-2 max-w-xs">{{ $bid->project->project_title }}</td>
                                <td class="p-2 ">₱{{ number_format($bid->project->amount, 2) }}</td>
                                <td class="p-2 whitespace-nowrap">{{ $bid->project->bidding_date->format('Y-m-d') }}</td>
                                <td class="p-2 ">{{ $bid->company_name}}</td>
                                <td class="p-2 whitespace-nowrap">{{ $bid->proprietor}}</td>
                                <td class="p-2 whitespace-nowrap">₱{{ number_format($bid->bid_amount, 2)}}</td>
                                <td class="p-2 capitalize">{{ $bid->address}}</td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex gap-3 h-full items-center  justify-center ">

                                        <button class="flex items-center hover:scale-110 transition"
                                            @click="deleteId = {{ $bid->id }}; showDeleteBidModal = true">
                                            <x-lucide-trash class="w-5 h-5 text-red-text cursor-pointer" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-5 text-center text-gray-500">
                                    No projects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $bids->links() }}
            </div>
        </div>

        <x-create-bid />
        <x-delete-bid />
    </div>
</x-app-layout>