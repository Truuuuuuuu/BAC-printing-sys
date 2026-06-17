<x-app-layout>

    <div class="max-w-[1440px] w-full mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-5 py-12">
        <div class="table-responsive w-full rounded-3xl border-2 border-white bg-foreground">
            <div class="flex justify-between mt-5 px-5">
                 <div class=" w-full">
                    <p class="text-2xl font-semibold tracking-wide text-primary">ALL USERS</p>
                </div>
                <form method="GET" class="w-full">
                    {{-- Search Form --}}
                    <div class="flex justify-between flex-col md:flex-row gap-3 lg:gap-0 items-center">
                        <div>

                        </div>
                        <div class="relative w-full md:max-w-md lg:max-w-xs xl:max-w-xl"
                            x-data="{ search: '{{ request('search') }}' }">

                            <form method="GET">
                                <input type="text" name="search" x-model="search" placeholder="Search User..."
                                    class="w-full border px-3 py-2 pr-20 rounded-3xl border border-gray-300">

                                {{-- Clear Input Search --}}
                                <button x-show="search.length > 0" x-cloak type="button" @click="
                                            search = '';
                                            window.location = '{{ route('admin.users') }}';
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
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 max-w-xs">Name</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60">Username</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 whitespace-nowrap">Role</th>
                            <th class="px-5 text-left py-1 font-medium text-sm opacity-60 whitespace-nowrap">Created at
                            </th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-t  hover:bg-gray-200 odd:bg-foreground even:bg-gray-100">
                                <td class="px-5 py-1 max-w-xs font-semibold">{{ $user->name }}</td>
                                <td class="px-5 py-1 opacity-80 font-light">{{'@' . $user->username}}</td>
                                <td class="px-5 py-1 opacity-80 whitespace-nowrap capitalize">
                                    {{ $user->getRoleNames()->first() ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-1 opacity-80 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    @if(auth()->user()->id !== $user->id)
                                    <div x-data="{ open: false }" class="relative inline-block"
                                        @click.outside="open = false">

                                        <button x-ref="btn" @click="open = !open" class="p-2 ">
                                            <x-heroicon-s-ellipsis-vertical class="w-5 h-5" />
                                        </button>

                                        <template x-teleport="body">
                                            <div x-show="open" x-transition
                                                class="fixed z-[9999] w-44 bg-white border border-gray-100 rounded-xl shadow-lg"
                                                :style="`top:${$refs.btn.getBoundingClientRect().bottom+8}px;left:${$refs.btn.getBoundingClientRect().right - 176}px`">
                                                <button @click="$dispatch('open-reset', { id: {{ $user->id }} })"
                                                    class="w-full px-4 py-2.5 flex justify-start gap-2 text-sm text-left text-gray-700 hover:bg-gray-50 rounded-t-xl">
                                                    <x-heroicon-s-key class="w-4 h-4" /> <span>Reset Password</span>
                                                </button>
                                                <hr class="border-gray-100">
                                                <button @click="$dispatch('open-delete', { id: {{ $user->id }} })"
                                                    class="w-full px-4 py-2.5 flex justify-start gap-2 text-sm text-left text-red-600 hover:bg-red-50 rounded-b-xl">
                                                    <x-heroicon-s-trash class="w-4 h-4" /> <span>Delete User</span>
                                                </button>
                                            </div>
                                        </template>
                                    @endif
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-5 text-center text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-reset-password-modal/>
            <x-delete-user-modal/>

            <div class="m-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>