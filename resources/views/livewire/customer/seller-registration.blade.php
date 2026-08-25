<div>
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('livewire.customer.start-selling') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Seller Registration</h1>
        </div>

        <!-- Steps -->
        <div class="flex items-center gap-4 mb-8">
            <div class="flex items-center gap-2">
                <span
                    class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-bold">1</span>
                <span class="text-sm {{ $step >= 1 ? 'text-gray-800' : 'text-gray-400' }}">Shop Info</span>
            </div>
            <div class="flex-1 h-0.5 {{ $step >= 2 ? 'bg-amber-600' : 'bg-gray-200' }}"></div>
            <div class="flex items-center gap-2">
                <span
                    class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-bold">2</span>
                <span class="text-sm {{ $step >= 2 ? 'text-gray-800' : 'text-gray-400' }}">Business Info</span>
            </div>
        </div>

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <!-- Step 1: Shop Info -->
        @if($step == 1)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">🏪 Shop Information</h2>
            <p class="text-sm text-gray-500 mb-6">Tell us about your bakeshop.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop Name</label>
                    <input type="text" wire:model="shop_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    @error('shop_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop Address</label>
                    <textarea wire:model="shop_address" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"></textarea>
                    @error('shop_address')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="contact_number" maxlength="11"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 @error('contact_number') border-red-500 @enderror"
                        placeholder="09123456789">
                    @error('contact_number')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Exactly 11 digits, starting with 09 (e.g., 09123456789)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop Description (Optional)</label>
                    <textarea wire:model="shop_description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                        placeholder="Tell customers about your bakeshop..."></textarea>
                    @error('shop_description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button wire:click="nextStep"
                    class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                    Next →
                </button>
            </div>
        </div>
        @endif

        <!-- Step 2: Business Info -->
        @if($step == 2)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📄 Business Information</h2>
            <p class="text-sm text-gray-500 mb-6">Upload your Business Permit from the LGU and a Valid Government ID.
            </p>

            <div class="space-y-4">
                <!-- Business Permit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Permit</label>
                    <input type="file" wire:model="business_permit"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="text-xs text-gray-400 mt-1">Accepted: PDF, JPG, PNG (Max 5MB)</p>
                    @error('business_permit')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    @if($business_permit)
                    <div class="mt-2">
                        @if(in_array($business_permit->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                        <img src="{{ $business_permit->temporaryUrl() }}"
                            class="w-32 h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition"
                            onclick="window.open('{{ $business_permit->temporaryUrl() }}', '_blank')">
                        <p class="text-xs text-gray-400 mt-1">Click image to enlarge</p>
                        @else
                        <p class="text-sm text-green-600">✅ {{ $business_permit->getClientOriginalName() }} uploaded
                            (PDF)</p>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Valid Government ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valid Government ID</label>
                    <input type="file" wire:model="valid_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="text-xs text-gray-400 mt-1">Accepted: PDF, JPG, PNG (Max 5MB)</p>
                    @error('valid_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    @if($valid_id)
                    <div class="mt-2">
                        @if(in_array($valid_id->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                        <img src="{{ $valid_id->temporaryUrl() }}"
                            class="w-32 h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition"
                            onclick="window.open('{{ $valid_id->temporaryUrl() }}', '_blank')">
                        <p class="text-xs text-gray-400 mt-1">Click image to enlarge</p>
                        @else
                        <p class="text-sm text-green-600">✅ {{ $valid_id->getClientOriginalName() }} uploaded (PDF)</p>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="flex gap-3 pt-2">
                    <button wire:click="previousStep"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        ← Back
                    </button>
                    <button wire:click="submit" wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                        <span wire:loading.remove>Submit Application</span>
                        <span wire:loading>Submitting...</span>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>