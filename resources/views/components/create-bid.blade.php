<div x-show="showCreateBidModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h2 class="text-3xl font-semibold text-primary">Create Bid</h2>
        <p class="text-md text-primary">Enter the bid details below and submit your bid.</p>

        <form action="{{ route('bidder.store') }}" method="POST" class="text-primary space-y-3">
            @csrf
            {{-- Hidden input for project ID --}}
            <input type="hidden" name="project_id" :value="selectedProjectId">
            <input type="hidden" name="project_amount" x-model="selectedProjectAmount">
            <div>
                <label for="project_title">Project title</label>
                <input id="project_title" type="text" name="project_title" :value="selectedProjectTitle" readonly
                    placeholder="e.g., Covered Court" class="w-full p-2 border rounded-xl ">
                @error('project_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company_name">Company Name</label>
                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}"
                    placeholder="e.g., Juan Company" class="w-full p-2 border rounded-xl ">
                @error('company_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="proprietor">Owner</label>
                <input id="proprietor" type="text" name="proprietor" value="{{ old('proprietor') }}"
                    placeholder="e.g., Juan Dela Cruz" class="w-full p-2 border rounded-xl ">
                @error('proprietor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address">Address</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}" placeholder="e.g., San Juan"
                    class="w-full p-2 border rounded-xl ">
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bid_amount">Bid Amount</label>

                <input id="bid_amount" type="number" name="bid_amount" value="{{ old('bid_amount') }}"
                    placeholder="e.g., 100000" class="w-full p-2 border rounded-xl ">
                <p class="text-xs">
                    Approved Budget:
                    ₱<span x-text="Number(selectedProjectAmount).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })"></span>
                </p>
                @error('bid_amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button type="button" @click="showCreateBidModal = false"
                    class="px-4 py-1 hover:border rounded-xl">Cancel</button>
                <button type="submit"
                    class="bg-bg-green font-semibold text-foreground hover:bg-primary/90 transition px-5 py-2 rounded-xl">Submit
                    Bid</button>
            </div>
        </form>
    </div>
</div>