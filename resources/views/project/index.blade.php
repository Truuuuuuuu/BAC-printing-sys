<x-app-layout>


    <div class="py-12" x-data="{
    editId: {{ old('edit-id', 'null') }},
    editProject: {
        project_title: '{{ old('edit-project_title') }}',
        amount: '{{ old('edit-amount') }}',
        bidding_date: '{{ old('edit-bidding_date') }}',
        status: '{{ old('edit-status') }}'
    },
    deleteId: null,
    showEditModal: {{ $errors->hasAny(['edit-project_title', 'edit-amount', 'edit-bidding_date', 'edit-status']) ? 'true' : 'false' }},
    showDeleteModal: false,
    }">
        <div class="max-w-[1440px] mx-auto sm:px-6 lg:px-8 flex gap-5">
            <div class=" max-w-md shrink-0 space-y-5">
                <div class="flex gap-2 items-center">
                    <div class="border rounded-2xl bg-foreground flex items-center justify-center p-5">
                        <x-lucide-folder-open-dot class="w-8 h-8 text-primary" />
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-3xl text-primary font-semibold">Create New Project</h2>
                        <p class="text-xs text-primary/80">Enter the information below to create a new project record.
                        </p>
                    </div>
                </div>

                <form action="{{ route('project.store') }}" method="POST" class="text-primary">
                    @csrf
                    <label for="project_title">Project title</label>
                    <input id="project_title" type="text" name="project_title" value="{{ old('project_title') }}"
                        placeholder="e.g., Covered Court" class="w-full p-2 border rounded-xl">
                    @error('project_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <label for="amount">Amount</label>
                    <input id="amount" type="number" name="amount" value="{{ old('amount') }}"
                        placeholder="e.g., 100000" class="w-full p-2 border rounded-xl ">
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <label for="bidding_date">Bidding date</label>
                    <input id="bidding_date" type="date" name="bidding_date" value="{{ old('bidding_date') }}"
                        class="w-full p-2 border rounded-xl ">
                    @error('bidding_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <label for="status">Status</label>
                    <select id="status" name="status" class="w-full p-2 border rounded-xl ">
                        <option value="awarded">Awarded</option>
                        <option value="failed">Failed</option>
                    </select>

                    <button type="submit"
                        class="w-full bg-bg-green font-semibold text-foreground py-2 rounded-xl mt-5 hover:bg-primary/90 transition">Create
                        Project</button>
                </form>
            </div>

            <div class="w-full border bg-foreground rounded-2xl p-5">
                <div class="flex justify-end">
                    <input type="text" placeholder="Search projects..." class="border p-2 rounded-xl w-2/3">
                </div>
                <table class="w-full mt-10">
                    <thead>
                        <tr>
                            <th class="text-left p-2 max-w-xs">Project Title</th>
                            <th class="text-left p-2 ">Amount</th>
                            <th class="text-left p-2 whitespace-nowrap">Bidding Date</th>
                            <th class="text-left p-2 whitespace-nowrap">Status</th>
                            <th class="text-left p-2 whitespace-nowrap">Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr class="border-t">
                                <td class="p-2 max-w-xs">{{ $project->project_title }}</td>
                                <td class="p-2 ">₱{{ number_format($project->amount, 2) }}</td>
                                <td class="p-2 whitespace-nowrap">{{ $project->bidding_date->format('Y-m-d') }}</td>
                                <td class="p-2 whitespace-nowrap capitalize">
                                    <div
                                        class="rounded-xl {{ $project->status === 'awarded' ? 'bg-bg-green/30 text-green-text/70' : 'bg-bg-red/30 text-red-text/70' }} font-semibold flex justify-center items-center px-1">
                                        {{ $project->status }}
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex gap-3 h-full">
                                        <a class="flex items-center hover:scale-110 transition"
                                            @click="editId = {{ $project->id }}; editProject = {{ json_encode($project) }};  editProject.bidding_date = '{{ $project->bidding_date->format('Y-m-d') }}'; showEditModal = true">
                                            <x-lucide-pencil class="w-5 h-5 text-primary cursor-pointer" />
                                        </a>


                                        <a class="flex items-center hover:scale-110 transition"
                                            @click="deleteId = {{ $project->id }}; showDeleteModal = true">
                                            <x-lucide-trash class="w-5 h-5 text-red-text cursor-pointer" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <x-edit-project />
            </div>
        </div>
    </div>
</x-app-layout>