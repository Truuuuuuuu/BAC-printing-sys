<div x-show="showCreateBidModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-5xl">
        <h2 class="text-3xl font-semibold text-primary">Create Bid</h2>
        <p class="text-md text-primary">Provide the bidder and project information to create a new bid..</p>

        <p class="text-xs text-primary/50">* All fields are required unless otherwise specified.</p>

        <form action="{{ route('bidder.store') }}" method="POST" class="text-primary space-y-3">
            @csrf
            {{-- Hidden input for project ID --}}
            <input type="hidden" name="project_id" :value="selectedProjectId">
            <input type="hidden" name="project_amount" x-model="selectedProjectAmount">
            <div class="w-full flex gap-7">
                <div class="flex-1 space-y-3">
                    <div>
                        <label for="project_title" class="font-semibold">Project title</label>
                        <textarea id="project_title" type="text" name="project_title" :value="selectedProjectTitle"
                            readonly placeholder="e.g., Covered Court"
                            class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl min-h-24 max-h-24 resize-none"> </textarea>
                        @error('project_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company_name" class="font-semibold">Company Name</label>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}"
                            placeholder="e.g., Juan Company" class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl ">
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proprietor" class="font-semibold">Owner/Proprietor</label>
                        <input id="proprietor" type="text" name="proprietor" value="{{ old('proprietor') }}"
                            placeholder="e.g., Juan Dela Cruz" class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl ">
                        @error('proprietor')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bid_amount" class="font-semibold">Bid Amount</label>

                        <input id="bid_amount" type="number" name="bid_amount" value="{{ old('bid_amount') }}"
                            placeholder="e.g., 100000" class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl ">
                        <div class="flex gap-1 items-center justify-start mt-1">
                            <x-heroicon-o-information-circle class="w-4 h-4"/>
                            <p class="text-xs ">
                                Approved Budget:
                                    ₱<span x-text="Number(selectedProjectAmount).toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    })"></span>
                            </p>
                        </div>

                        @error('bid_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class=" w-full max-w-96">
                    <div x-data="{
                            selectedCityId: '',
                            selectedCityName: '',
                            selectedBarangay: '',
                            barangays: [],
                            async fetchBarangays(cityId) {
                                if (!cityId) { this.barangays = []; return; }
                                const res = await fetch(`/api/barangays/${cityId}`);
                                this.barangays = await res.json();
                                this.selectedBarangay = '';
                            }
                        }" class="flex-1 space-y-3">
                        {{-- Street --}}
                        <div>
                            <label for="street" class="font-semibold">Street</label>
                            <input id="street" type="text" name="street" value="{{ old('street') }}"
                                placeholder="e.g., Employees Housing Diversion Road"
                                class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl">
                            @error('street')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Municipality/City --}}
                        <div class="relative" x-data="{ open: false }">
                            <label class="font-semibold">Municipality/City</label>

                            {{-- Display button --}}
                            <button type="button" @click="open = !open"
                                class="w-full p-2 border-2 border-gray-300 shadow-sm  rounded-xl bg-white text-left flex justify-between items-center">
                                <span x-text="selectedCityName || 'Select Municipality/City'"
                                    :class="selectedCityName ? 'text-primary' : 'text-gray-400'">
                                </span>
                                <x-lucide-chevron-down class="w-4 h-4 text-primary" />
                            </button>

                            {{-- Hidden input stores name string --}}
                            <input type="hidden" name="municipality_city" :value="selectedCityName" >

                            {{-- Dropdown list --}}
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute z-50 w-full bg-white border rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto">
                                @foreach($cities as $city)
                                    <div @click="
                                            selectedCityId = '{{ $city->id }}';
                                            selectedCityName = '{{ $city->name }}';
                                            selectedBarangay = '';
                                            fetchBarangays('{{ $city->id }}');
                                            open = false
                                        " class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                                        :class="selectedCityName === '{{ $city->name }}' ? 'bg-gray-100 font-semibold' : ''">
                                        {{ $city->name }}
                                    </div>
                                @endforeach
                            </div>

                            @error('municipality_city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Barangay --}}
                        <div class="relative" x-data="{ open: false }">
                            <label class="font-semibold">Barangay</label>

                            {{-- Display button --}}
                            <button type="button" @click="open = !open"
                                class="w-full p-2 border-2 border-gray-300 shadow-sm  rounded-xl bg-white text-left flex justify-between items-center">
                                <span x-text="selectedBarangay || 'Select Barangay'"
                                    :class="selectedBarangay ? 'text-primary' : 'text-gray-400'">
                                </span>
                                <x-lucide-chevron-down class="w-4 h-4 text-primary" />
                            </button>

                            {{-- Hidden input for form submission --}}
                            <input type="hidden" name="barangay" :value="selectedBarangay">

                            {{-- Dropdown list --}}
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute z-50 w-full bg-white border rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto">
                                <template x-if="barangays.length === 0">
                                    <div class="p-2 text-gray-400 text-sm text-center">
                                        Select a city/municipality first
                                    </div>
                                </template>
                                <template x-for="b in barangays" :key="b.id">
                                    <div @click="selectedBarangay = b.name; open = false"
                                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                                        :class="selectedBarangay === b.name ? 'bg-gray-100 font-semibold' : ''"
                                        x-text="b.name">
                                    </div>
                                </template>
                            </div>

                            @error('barangay')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

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