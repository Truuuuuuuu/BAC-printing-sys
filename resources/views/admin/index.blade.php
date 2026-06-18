<x-app-layout>
    <div class="max-w-[1440px] w-full mx-auto px-3 lg:px-8 flex flex-col gap-5 py-12">
        <div class="flex flex-col md:flex-row gap-3 justify-between w-full">
            <x-dash-top-cards label="Total Users" :value="$totalUsers" :icon="'heroicon-s-users'" />
            <x-dash-top-cards label="Total Projects" :value="$totalProjects" :icon="'heroicon-s-folder-open'" />
            <x-dash-top-cards label="Total Bids" :value="$totalBids" :icon="'heroicon-s-document'" />
        </div>

        <div class="flex flex-col md:flex-row w-full gap-3">
            <div class="w-full border-2 bg-gray-100 rounded-2xl p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold mb-3">Latest Projects</h1>
                    <a href="{{ route('project.index') }}"
                        class="block text-primary cursor-pointer hover:scale-105 flex gap-2 items-end hover:underline hover:font-semibold">
                        <p class="text-sm">VIEW ALL</p>

                    </a>
                </div>
                @forelse ($projects as $project)
                    <x-project-card :$project />
                @empty
                    <div class="w-full flex justify-center italic ">
                        <p class="opacity-50">No available project records</p>
                    </div>
                @endforelse

            </div>
            <div class="w-full border-2 bg-gray-100 rounded-2xl p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold mb-3">New Bids</h1>
                    <a href="{{ route('bidder.index') }}"
                        class="block text-primary cursor-pointer hover:scale-105 flex gap-2 items-end hover:underline hover:font-semibold">
                        <p class="text-sm">VIEW ALL</p>

                    </a>
                </div>

                @forelse ($bids as $bid)
                    @if($bid->project)
                        <x-bid-card :$bid />
                    @else
                        <div class="w-full flex justify-center italic ">
                            <p class="opacity-50">No available bid records</p>
                        </div>
                    @endif
                @empty
                    <div class="w-full flex justify-center italic ">
                        <p class="opacity-50">No available bid records</p>
                    </div>
                @endforelse
            </div>
        </div>


    </div>

    <x-edit-project />
    <x-delete-project />


</x-app-layout>