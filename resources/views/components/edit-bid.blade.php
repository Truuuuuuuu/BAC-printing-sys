<div x-show="showEditBidModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-5xl">
        <h2 class="text-3xl font-semibold text-primary">Edit Bid</h2>
        <p class="text-md text-primary/70">Review and update bid information.</p>

        <form :action="`/bidder/${editId}/edit`" method="POST" class="mt-4 space-y-3 text-primary">
            @csrf
            @method('PUT')
            <input type="hidden" name="edit-id" :value="editId">
            <div class="w-full flex gap-7 mb-20">
                <div class="flex-1 space-y-3">
                    <div>
                        <label class=" font-semibold">Company Name</label>
                        <input type="text" name="edit-company_name" x-model="editBid.company_name"
                            class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl">
                    </div>
                    @error('edit-company_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div>
                        <label class=" font-semibold">Owner</label>
                        <input type="text" name="edit-proprietor" x-model="editBid.proprietor"
                            class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl">
                    </div>
                    @error('edit-proprietor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <div>
                        <label class=" font-semibold">Bid Amount</label>
                        <input type="text" name="edit-bid_amount" x-model="editBid.bid_amount"
                            class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl">
                    </div>
                    @error('edit-bid_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class=" w-full max-w-96 space-y-3">
                    <div x-data="{
                        cities: {{ json_encode($cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name])) }},
                        selectedCityId: '',
                        selectedCityName: '{{ old('edit-municipality_city') ? str_replace(', Sorsogon', '', old('edit-municipality_city')) : '' }}',
                        selectedBarangay: '{{ old('edit-barangay') }}',
                        barangays: [],
                        openCity: false,
                        openBarangay: false,

                        async fetchBarangays(cityId) {
                            if (!cityId) {
                                this.barangays = [];
                                return;
                            }

                            const res = await fetch(`/api/barangays/${cityId}`);

                            if (!res.ok) {
                                console.error('Failed to load barangays', res.status);
                                this.barangays = [];
                                return;
                            }

                            this.barangays = await res.json();
                        },

                        async initEdit(municipalityCity, barangay) {
                            if (!municipalityCity) return;
                            const cityName = municipalityCity.replace(', Sorsogon', '').trim();
                            const city = this.cities.find(c => c.name === cityName);
                            if (city) {
                                this.selectedCityId = city.id;
                                this.selectedCityName = city.name;
                                await this.fetchBarangays(city.id);
                            }
                            this.selectedBarangay = barangay;
                        }

                        }" 
                        x-init="

                        const self = $data;

                        window.addEventListener('open-edit', async (e) => {
                            self.selectedCityId = '';
                            self.selectedCityName = '';
                            self.selectedBarangay = '';
                            self.barangays = [];

                            await self.initEdit(
                                e.detail.municipality_city,
                                e.detail.barangay
                            );
                        });

                        @if($errors->hasAny(['edit-company_name', 'edit-proprietor', 'edit-bid_amount', 'edit-street', 'edit-barangay', 'edit-municipality_city']))
                            await self.initEdit(
                                '{{ old('edit-municipality_city') }}',
                                '{{ old('edit-barangay') }}'
                            );
                        @endif
                        "
                        class="flex-1 space-y-3">

                        {{-- Street --}}
                        <div>
                            <label class="font-semibold">Street</label>
                            <input type="text" name="edit-street" x-model="editBid.street"
                                placeholder="e.g., Employees Housing Diversion Road"
                                class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl">
                            @error('edit-street')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Municipality/City --}}
                        <div class="relative">
                            <label class="font-semibold">Municipality/City</label>
                            <button type="button" @click="openCity = !openCity"
                                class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl bg-white text-left flex justify-between items-center">
                                <span x-text="selectedCityName || 'Select Municipality/City'"
                                    :class="selectedCityName ? 'text-primary' : 'text-gray-400'"></span>
                                <x-lucide-chevron-down class="w-4 h-4 text-primary" />
                            </button>

                            <input type="hidden" name="edit-municipality_city" :value="selectedCityName">

                            <div x-show="openCity" @click.outside="openCity = false" x-transition
                                class="absolute z-50 w-full bg-white border rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto">
                                <template x-for="city in cities" :key="city.id">
                                    <div @click="
                                            selectedCityId = city.id;
                                            selectedCityName = city.name;
                                            selectedBarangay = '';
                                            fetchBarangays(city.id);
                                            openCity = false
                                        " class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                                        :class="selectedCityName === city.name ? 'bg-gray-100 font-semibold' : ''"
                                        x-text="city.name">
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Barangay --}}
                        <div class="relative">
                            <label class="font-semibold">Barangay</label>

                            <button type="button" @click="openBarangay = !openBarangay"
                                class="w-full p-2 border-2 border-gray-300 shadow-sm rounded-xl bg-white text-left flex justify-between items-center">
                                <span x-text="selectedBarangay || 'Select Barangay'"
                                    :class="selectedBarangay ? 'text-primary' : 'text-gray-400'">
                                </span>
                                <x-lucide-chevron-down class="w-4 h-4 text-primary" />
                            </button>

                            <input type="hidden" name="edit-barangay" :value="selectedBarangay">

                            <div x-show="openBarangay" @click.outside="openBarangay = false" x-transition
                                class="absolute z-50 w-full bg-white border rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto">
                                <template x-if="barangays.length === 0">
                                    <div class="p-2 text-gray-400 text-sm text-center">
                                        Select a city/municipality first
                                    </div>
                                </template>
                                <template x-for="b in barangays" :key="b.id">
                                    <div @click="selectedBarangay = b.name; openBarangay = false"
                                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                                        :class="selectedBarangay === b.name ? 'bg-gray-100 font-semibold' : ''"
                                        x-text="b.name">
                                    </div>
                                </template>
                            </div>

                            @error('edit-barangay')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>


            <div class="flex justify-end gap-3  py-5 ">
                <button type="button" @click="showEditBidModal = false"
                    class="px-4 py-1 hover:border rounded-xl">Cancel</button>
                <button type="submit"
                    class="bg-bg-green font-semibold text-foreground hover:bg-primary/90 transition px-5 py-2 rounded-xl">Save
                    Changes</button>
            </div>
        </form>
    </div>
</div>