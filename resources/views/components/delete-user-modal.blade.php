<div x-data="{ show: false, userId: null }" @open-delete.window="show = true; userId = $event.detail.id"
    x-show="show" x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40" @click.self="show = false">
    <div class="bg-white rounded-2xl p-6 w-sm shadow-xl" @click.stop>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-lg">🗑</div>
            <h3 class="font-medium text-gray-900">Delete Account</h3>
        </div>

        <p class="text-sm text-gray-500 mb-4 leading-relaxed">
            This will permanently delete the account and all associated data. This action <strong
                class="text-gray-700">cannot be undone</strong>.
        </p>

        <form x-bind:action="'{{ route('admin.users.delete', '_id_') }}'.replace('_id_', userId)" method="POST">
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