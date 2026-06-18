<div x-data="{ show: false, deleteUrl: null }" @open-delete.window="show = true; deleteUrl = $event.detail.url"
    x-show="show" x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40" @click.self="show = false">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl" @click.stop>

        <div class="flex items-start gap-3 mb-4">
            
            <h3 class="font-semibold text-primary text-3xl">Delete Account</h3>
        </div>

        <p class="text-sm text-gray-500 mb-4 leading-relaxed">
            This will permanently delete the account and all associated data. This action <strong
                class="text-gray-700">cannot be undone</strong>.
        </p>

        <form x-bind:action="deleteUrl" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex gap-2">
                <button type="button" @click="show = false"
                    class="flex-1 py-2.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Yes, Delete
                </button>
            </div>
        </form>

    </div>
</div>