<div x-data="{ show: false, pw: '', showPw: false, resetUrl: null }"
    @open-reset.window="show = true; resetUrl = $event.detail.url" x-show="show" x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40" @click.self="show = false; pw = ''">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl" @click.stop>

        <div class="flex items-center justify-between mb-4">
            <div class="flex  justify-start items-center font-medium text-gray-900">
                
                <p class="text-3xl font-semibold text-primary">Reset Password</p>
            </div>
            <button @click="show = false; pw = ''" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>

        <p class="text-sm text-gray-500 mb-4">Set a new password for this user.</p>

        <form x-bind:action="resetUrl" method="POST">
            @csrf

            <label class="text-xs text-gray-500 mb-1 block">New password</label>
            <div class="relative">
                <input :type="showPw ? 'text' : 'password'" name="password" x-model="pw"
                    placeholder="Min. 8 characters"
                    class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                <button @click="showPw = !showPw" type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs">
                    <span x-show="!showPw">@svg('heroicon-o-eye', 'w-4 h-4 text-gray-800')</span>
                    <span x-show="showPw">@svg('heroicon-o-eye-slash', 'w-4 h-4 text-gray-400')</span>
                </button>
            </div>

            <div class="flex gap-2 mt-4">
                <button @click="show = false; pw = ''" type="button"
                    class="flex-1 py-2.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" :disabled="pw.length < 8"
                    class="flex-1 py-2.5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary/90 disabled:opacity-40 disabled:cursor-not-allowed">
                    Save
                </button>
            </div>
        </form>

    </div>
</div>