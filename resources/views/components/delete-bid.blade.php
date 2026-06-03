<div x-show="showDeleteBidModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-3xl font-semibold text-primary">Delete Bid</h2>
        <p class="text-md text-primary">Are you sure you want to delete this bid?</p>
        <div class="flex gap-3 mt-8 justify-end">
            <button @click="showDeleteBidModal = false" class="px-4 py-1 hover:border rounded-xl">Cancel</button>
            <form :action="`/bidder/${deleteId}/delete`" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-bg-red font-semibold text-white rounded-xl">Delete Bid</button>
            </form>
        </div>
    </div>
</div>