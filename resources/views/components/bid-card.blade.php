

<a href="{{ route('project.show', ['project' => $bid->project, 'from' => url()->current()]) }}" 
   class="block border rounded-2xl border-2 border-white p-5 space-y-1 bg-foreground hover:bg-gray-200 hover:border-primary transition">
    <div>
        <div class="inline-flex gap-1 items-center bg-blue-100 text-primary rounded-xl px-2 py-1 text-xs">
            <x-heroicon-s-user class="w-4 h-4"/> <span>{{ $bid->proprietor }}</span>
        </div>
        <h3 class="text-lg font-semibold text-primary">{{ $bid->company_name}}</h3>
        <p class="text-xs italic">{{ $bid->project->project_title }}</p>
    </div>

    <div class="flex gap-1 items-center text-primary">
        <x-heroicon-s-wallet class="w-4 h-4" />
        <p class="text-md text-primary font-medium">₱{{ number_format($bid->bid_amount, 2) }}</p>
    </div>
</a>
