<div x-show="showEditBidModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-3xl font-semibold text-primary">Edit Bid</h2>
        <p class="text-md text-primary/70">Review and update bid information.</p>

        <form :action="`/bidder/${editId}/edit`"  method="POST" class="mt-4 space-y-3 text-primary">
            @csrf
            @method('PUT')
            <input type="hidden" name="edit-id" :value="editId">
            <div>
                <label class="text-sm">Company Name</label>
                <input type="text" name="edit-company_name" x-model="editBid.company_name"
                    class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-company_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label class="text-sm">Owner</label>
                <input type="text" name="edit-proprietor" x-model="editBid.proprietor" class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-proprietor')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label class="text-sm">Address</label>
                <input type="text" name="edit-address" x-model="editBid.address" class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label class="text-sm">Bid Amount</label>
                <input type="text" name="edit-bid_amount" x-model="editBid.bid_amount" class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-bid_amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            
            <div class="flex justify-end gap-3 ">
                <button type="button" @click="showEditBidModal = false" class="px-4 py-1 hover:border rounded-xl">Cancel</button>
                <button type="submit"
                    class="bg-bg-green font-semibold text-foreground hover:bg-primary/90 transition px-5 py-2 rounded-xl">Save
                    Changes</button>
            </div>
        </form>
    </div>
</div>