<div x-show="showRemoveAwardModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-3xl font-semibold text-primary">Remove Award Contract</h2>
        <p class="text-sm text-muted-foreground">
            Are you sure you want to remove the award for this bidder? 
        </p>
        <div class="flex gap-3 mt-8 justify-end">
            <button @click="showRemoveAwardModal = false" class="px-4 py-1 hover:border rounded-xl">Cancel</button>
            <form :action="`/project/${bidId}/remove-award`" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 bg-bg-red font-semibold text-white rounded-xl hover:bg-primary">Remove Award</button>
            </form>
        </div>
    </div>
</div>