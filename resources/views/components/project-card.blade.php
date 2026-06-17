@props(['project'])

<a href="{{ route('project.show', ['project' => $project, 'from' => url()->current()]) }}" 
   class="block border rounded-2xl border-2 border-white p-5 space-y-1 bg-foreground hover:bg-gray-200 hover:border-primary hover:scale-105 transition">
    <div>
        <div class="inline-block bg-blue-100 text-primary rounded-xl px-2 text-xs">
            {{ $project->created_at->format('F j, Y') }}
        </div>
        <h3 class="text-lg font-semibold text-primary">{{ $project->project_title }}</h3>
    </div>

    <div class="flex gap-1 items-center text-primary">
        <x-heroicon-s-wallet class="w-4 h-4" />
        <p class="text-md text-primary font-medium">₱{{ number_format($project->approved_budget, 2) }}</p>
    </div>
</a>

