@props(['project'])

<div>
    <div class="border rounded-2xl p-5 space-y-3 bg-foreground">
        <div>
            <div class="inline-block bg-blue-100 text-primary rounded-xl px-2 text-xs">
                {{ $project->created_at->format('F j, Y') }}
            </div>
            <h3 class="text-xl font-semibold text-primary">{{ $project->project_title }}</h3>
        </div>

        <div class="flex gap-1 items-center text-primary">
            <x-lucide-wallet class="w-4 h-4" />
            <p class="text-md text-primary font-bold">₱{{ number_format($project->amount, 2) }}</p>
        </div>

        <button
            class="relative w-full bg-bg-green hover:bg-primary hover:scale-105 text-foreground font-semibold rounded-3xl py-1 hover:shadow-md transition-all duration-200"
            @click="showCreateBidModal = true; selectedProjectTitle='{{ $project->project_title }}'; selectedProjectId='{{ $project->id }}'; selectedProjectAmount='{{ $project->amount }}'">

            <span class="block text-center">
                New Bid
            </span>

            <x-lucide-badge-plus class="w-6 h-6 absolute right-1 top-1/2 -translate-y-1/2" />
        </button>
    </div>
</div>