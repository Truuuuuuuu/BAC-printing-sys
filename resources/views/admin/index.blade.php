<x-app-layout>
    <div class="max-w-[1440px] w-full mx-auto sm:px-6 lg:px-8 flex flex-col gap-5 py-12">
        <div class="flex gap-3 justify-between w-full">
            <x-dash-top-cards label="Total Users" :value="$totalUsers" :icon="'heroicon-s-users'" />
            <x-dash-top-cards label="Total Projects" :value="$totalProjects" :icon="'heroicon-s-folder-open'" />
            <x-dash-top-cards label="Total Bids" :value="$totalBids" :icon="'heroicon-s-document'" />
        </div>

        <div class="flex w-full gap-3">
            <div class="w-full border-2 bg-gray-100 rounded-2xl p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold mb-3">Latest Projects</h1>
                    <a href="{{ route('project.index') }}"
                        class="bg-primary px-3 rounded-xl text-foreground cursor-pointer hover:scale-105 flex gap-2 relative w-full max-w-[100px]">
                        <p>View all</p>
                        <x-lucide-circle-arrow-right class="w-4 h-4 absolute right-1 top-1/2 -translate-y-1/2" />
                    </a>
                </div>
                @forelse ($projects as $project)
                    <x-project-card :$project />
                @empty
                    <p>No available projects</p>
                @endforelse

            </div>
            <div class="w-full border-2 bg-gray-100 rounded-2xl p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold mb-3">New Bids</h1>
                    <a href="{{ route('bidder.index') }}"
                        class="bg-primary px-3 rounded-xl text-foreground cursor-pointer hover:scale-105 relative w-full max-w-[100px]">
                        <p>View all</p>
                        <x-lucide-circle-arrow-right class="w-4 h-4 absolute right-1 top-1/2 -translate-y-1/2" />

                    </a>
                </div>  
                @forelse ($bids as $bid)
                    <x-bid-card :$bid />
                @empty
                    <p>No available projects</p>
                @endforelse
            </div>
        </div>


    </div>

    <x-edit-project />
    <x-delete-project />


</x-app-layout>