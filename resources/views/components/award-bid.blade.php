<div x-show="showAwardModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-3xl font-semibold text-primary">Award Contract</h2>
        <p class="text-md text-primary">Are you sure you want to declare this bidder as the awardee for this project?</p>
        <div class="flex gap-3 mt-8 justify-end">
            <button @click="showAwardModal = false" class="px-4 py-1 hover:border rounded-xl">Cancel</button>
            <form :action="`/project/${bidId}/award`" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 bg-bg-green font-semibold text-white rounded-xl hover:bg-primary">Confirm Award</button>
            </form>
        </div>
    </div>
</div>