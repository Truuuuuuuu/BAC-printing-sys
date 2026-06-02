<div x-show="showEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-3xl font-semibold text-primary">Edit Project</h2>
        <p class="text-md text-primary">Review and update project information.</p>

        <form :action="`/project/${editId}/edit`" method="POST" class="mt-4 space-y-3 text-primary">
            @csrf
            @method('PUT')
            <input type="hidden" name="edit-id" :value="editId">
            <div>
                <label>Project Title</label>
                <input type="text" name="edit-project_title" x-model="editProject.project_title"
                    class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-project_title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label>Amount</label>
                <input type="number" name="edit-amount" x-model="editProject.amount" class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-amount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label>Bidding Date</label>
                <input type="date" name="edit-bidding_date" x-model="editProject.bidding_date"
                    class="w-full p-2 border rounded-xl">
            </div>
            @error('edit-bidding_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label>Status</label>
                <select name="edit-status" x-model="editProject.status" class="w-full p-2 border rounded-xl">
                    <option value="awarded">Awarded</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            @error('edit-status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            
            <div class="flex justify-end gap-3 ">
                <button type="button" @click="showEditModal = false" class="px-4 py-1 hover:border rounded-xl">Cancel</button>
                <button type="submit"
                    class="bg-bg-green font-semibold text-foreground hover:bg-primary/90 transition px-5 py-2 rounded-3xl">Save
                    Changes</button>
            </div>
        </form>
    </div>
</div>