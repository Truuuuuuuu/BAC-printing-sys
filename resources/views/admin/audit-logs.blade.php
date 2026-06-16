<x-app-layout>

    <div class="max-w-[1440px] w-full mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-5 py-12">
        <div class="table-responsive w-full rounded-3xl border-2 border-white bg-foreground">
            <div class="flex justify-end mt-5 px-5">
                <form method="GET" class="w-full">
                    {{-- Search Form --}}
                    <div class="flex justify-between flex-col md:flex-row gap-3 lg:gap-0 items-center">
                        <div>

                        </div>
                        <div class="relative w-full md:max-w-md lg:max-w-xs xl:max-w-xl"
                            x-data="{ search: '{{ request('search') }}' }">

                            <form method="GET">
                                <input type="text" name="search" x-model="search" placeholder="Search Audit Logs..."
                                    class="w-full border px-3 py-2 pr-20 rounded-3xl border border-gray-300">

                                {{-- Clear Input Search --}}
                                <button x-show="search.length > 0" x-cloak type="button" @click="
                                            search = '';
                                            window.location = '{{ route('admin.audit-logs') }}';
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
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 max-w-xs">Date</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60">User</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 whitespace-nowrap">Activity
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditLogs as $auditLog)
                            <tr class="border-t hover:cursor-pointer hover:bg-gray-200 odd:bg-foreground even:bg-gray-100">
                                <td class="px-5 py-1 max-w-xs">
                                    <div class="flex items-center gap-2">
                                        {{ $auditLog?->created_at?->format('M d, Y h:i A') }}
                                        <div class="w-1 h-1 bg-black rounded-full opacity-50"></div>
                                        <span class="text-xs opacity-70">
                                            {{ $auditLog?->created_at?->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-1 opacity-80">{{ $auditLog->user?->name ?? 'System'}}</td>
                                <td class="px-5 py-1 opacity-80 whitespace-nowrap">{!!  $auditLog->activity !!}</td>

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
                {{ $auditLogs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>