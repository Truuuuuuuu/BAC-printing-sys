<x-app-layout>
    <div class="max-w-[1440px] w-full mx-auto sm:px-6 lg:px-8 flex flex-col gap-5 py-12"
        x-data="{
    editId: {{ old('edit-id', 'null') }},
    editProject: {
        project_title: '{{ old('edit-project_title') }}',
        approved_budget: '{{ old('edit-approved_budget') }}',
        bidding_date: '{{ old('edit-bidding_date') }}',
        status: '{{ old('edit-status') }}'
    },
    deleteId: null,
    showEditModal: {{ $errors->hasAny(['edit-project_title', 'edit-approved_budget', 'edit-bidding_date', 'edit-status']) ? 'true' : 'false' }},
    showDeleteModal: false,
    }">

        <div class="table-responsive w-full rounded-3xl border-2 border-white bg-foreground">
            <div class="flex justify-end mt-5 px-5">
                <form method="GET" class="w-full">
                    {{-- Search Form --}}
                    <div class="flex justify-between flex-col md:flex-row gap-3 lg:gap-0 items-center">
                        <div>
                            {{-- Projects View & Print button --}}
                            {{-- <a href="{{ route('pdf.projects') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-3xl
                                hover:bg-primary/80 hover:shadow-sm hover:scale-105 transition text-sm">
                                <x-lucide-printer class="w-5 h-5 text-foreground" />
                                <span>View & Print All</span>
                            </a> --}}
                        </div>
                        <div class="relative w-full md:max-w-md lg:max-w-xs xl:max-w-xl"
                            x-data="{ search: '{{ request('search') }}' }">

                            <form method="GET">
                                <input type="text" name="search" x-model="search" placeholder="Search projects..."
                                    class="w-full border px-3 py-2 pr-20 rounded-3xl border border-gray-300">

                                {{-- Clear Input Search --}}
                                <button x-show="search.length > 0" x-cloak type="button" @click="
                                            search = '';
                                            window.location = '{{ route('admin.index') }}';
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
            <div class="overflow-x-auto w-full mt-5">
                <table class="w-full ">
                    <thead>
                        <tr>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 max-w-xs">Project Title</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60">Amount</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 whitespace-nowrap">Bidding
                                Date</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 whitespace-nowrap">Status</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 whitespace-nowrap">Actions
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr class="border-t hover:cursor-pointer hover:bg-gray-200 odd:bg-foreground even:bg-gray-100"
                                onclick="window.location='{{ route('project.show', ['project' => $project, 'from' => url()->current()]) }}'">
                                <td class="px-5 py-1 max-w-xs">{{ $project->project_title }}</td>
                                <td class="px-5 py-1 opacity-80">₱{{ number_format($project->approved_budget, 2) }}</td>
                                <td class="px-5 py-1 opacity-80 whitespace-nowrap">
                                    {{ $project->bidding_date->format('Y-m-d') }}</td>
                                <td class="px-5 py-1 whitespace-nowrap capitalize">
                                    <div
                                        class="rounded-xl {{ $project->status === 'awarded' ? 'bg-bg-green/30 text-green-text/70' : 'bg-bg-red/30 text-red-text/70' }} font-semibold text-xs flex justify-center items-center ">
                                        {{ $project->status }}
                                    </div>
                                </td>
                                <td class="px-5 py-1 whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="flex gap-3 h-full">
                                        <button class="flex items-center hover:scale-110 transition"
                                            @click="editId = {{ $project->id }}; editProject = {{ json_encode($project) }};  editProject.bidding_date = '{{ $project->bidding_date->format('Y-m-d') }}'; showEditModal = true">
                                            <x-lucide-pencil class="w-5 h-5 text-primary cursor-pointer" />
                                        </button>


                                        <button class="flex items-center hover:scale-110 transition"
                                            @click="deleteId = {{ $project->id }}; showDeleteModal = true">
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
            </div>

            <x-edit-project />
            <x-delete-project />
            <div class="m-4">
                {{ $projects->links() }}
            </div>
        </div>
    </div>

    <x-edit-project/>
    <x-delete-project/>


</x-app-layout>