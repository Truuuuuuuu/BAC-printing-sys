<x-app-layout>
   

    <div class="py-12">
        <div class="max-w-[1440px] mx-auto sm:px-6 lg:px-8 flex gap-5">
            <div class=" max-w-md shrink-0 space-y-5">
                <div class="flex gap-2 items-center">
                    <div class="border rounded-2xl bg-foreground flex items-center justify-center p-5">
                        <x-lucide-folder-open-dot class="w-8 h-8 text-primary"/>  
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-3xl text-primary font-semibold">Create New Project</h2>
                        <p class="text-xs text-primary/80">Enter the information below to create a new project record.</p>
                    </div>
                </div>

                <form action="{{ route('project.store') }}" method="POST" class="text-primary">
                    @csrf
                    <label for="project_title">Project title</label>
                    <input id="project_title" type="text" name="project_title" value="{{ old('project_title') }}" placeholder="e.g., Covered Court" class="w-full p-2 border rounded-xl" >
                    @error('project_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <label for="amount">Amount</label>
                    <input id="amount" type="number" name="amount" value="{{ old('amount') }}" placeholder="e.g., 100000" class="w-full p-2 border rounded-xl " >
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <label for="bidding_date">Bidding date</label>
                    <input id="bidding_date" type="date" name="bidding_date" value="{{ old('bidding_date') }}" class="w-full p-2 border rounded-xl " >
                    @error('bidding_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <label for="status">Status</label>
                    <select id="status" name="status" class="w-full p-2 border rounded-xl " >
                        <option value="awarded">Awarded</option>
                        <option value="failed">Failed</option>
                    </select>

                    <button type="submit" class="w-full bg-bg-green font-semibold text-foreground py-2 rounded-xl mt-5 hover:bg-primary/90 transition">Create Project</button>
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
                                <td class="p-2 ">{{ number_format($project->amount, 2) }}</td>
                                <td class="p-2 whitespace-nowrap">{{ $project->bidding_date->format('F j, Y') }}</td>
                                <td class="p-2 whitespace-nowrap capitalize">{{ $project->status }}</td>
                                <td class="p-2 whitespace-nowrap ">
                                    <div class="flex gap-3 h-full">
                                        <a class="flex items-center">
                                            <x-lucide-pencil class="w-5 h-5 text-primary cursor-pointer" />
                                        </a>

                                        <a class="flex items-center">
                                            <x-lucide-trash class="w-5 h-5 text-red-text cursor-pointer" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
