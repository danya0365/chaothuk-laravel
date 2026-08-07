<div class="max-w-3xl mx-auto px-3 sm:px-4 py-4 md:py-6"
     x-data="{
         uploading: false,
         uploadError: '',
         async uploadImage(file, target) {
             this.uploading = true;
             this.uploadError = '';
             const formData = new FormData();
             formData.append('image', file);
             try {
                 const res = await fetch('/api/upload/image', {
                     method: 'POST',
                     headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                     body: formData,
                 });
                 const json = await res.json();
                 if (json.status && json.data) {
                     const url = json.data.resize || json.data.original;
                     if (target === 'primary') {
                         @this.set('primaryImage', url);
                     } else if (target === 'gallery') {
                         @this.set('galleryImages', [...@this.get('galleryImages'), url]);
                     }
                 } else {
                     this.uploadError = json.message || 'อัพโหลดไม่สำเร็จ';
                 }
             } catch (e) {
                 this.uploadError = 'เกิดข้อผิดพลาดในการอัพโหลด';
             }
             this.uploading = false;
         }
     }">

    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
        <a href="{{ route('frontend.works') }}?tab=my" class="text-xs md:text-sm text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-lg md:text-2xl font-bold text-white">📦 สร้างงานใหม่</h1>
    </div>

    @if($isBlocked)
        <div class="bg-gray-900 rounded-2xl p-6 text-center space-y-4">
            <div class="text-4xl">🔒</div>
            <h2 class="text-xl font-bold text-white">ฟีเจอร์นี้ถูกล็อค</h2>
            <p class="text-gray-400 text-sm">คุณยังไม่มีสิทธิในการสร้างงานใหม่ กรุณาติดต่อผู้ดูแลระบบหรือทำการยืนยันตัวตนให้ครบถ้วนก่อนใช้งาน</p>
        </div>
    @else
    <form wire:submit="submit" class="space-y-3 md:space-y-4">
        <div class="bg-gray-900 rounded-2xl p-4 md:p-5 space-y-3 md:space-y-4">

            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">ชื่องาน *</label>
                <input wire:model="title" type="text" placeholder="เช่น รถกระบะรับจ้างขนส่ง"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-white focus:outline-none focus:border-orange-500">
                @error('title')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">รหัสงาน (Code) * <span class="text-gray-600 text-[10px] md:text-xs">เช่น ทะเบียนรถ หรือรหัสเฉพาะ</span></label>
                <input wire:model="code" type="text" placeholder="เช่น กก-1234 หรือ ABC123"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-white focus:outline-none focus:border-orange-500 uppercase">
                @error('code')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">รายละเอียด *</label>
                <textarea wire:model="description" rows="4" placeholder="อธิบายบริการของคุณ..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-white focus:outline-none focus:border-orange-500 resize-none"></textarea>
                @error('description')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">ราคา (บาท) *</label>
                <input wire:model="price" type="number" step="0.01" placeholder="0.00"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-white focus:outline-none focus:border-orange-500">
                @error('price')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Primary Image Upload --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1 md:mb-2">📸 รูปหลัก</label>
                @if($primaryImage)
                    <div class="relative rounded-xl overflow-hidden mb-2">
                        <img src="{{ $primaryImage }}" class="w-full h-32 md:h-48 object-cover rounded-xl" alt="preview">
                        <button type="button" wire:click="removePrimaryImage"
                                class="absolute top-2 right-2 w-6 h-6 md:w-7 md:h-7 bg-red-500 hover:bg-red-400 text-white rounded-full flex items-center justify-center text-xs md:text-sm transition">✕</button>
                    </div>
                @else
                    <label class="flex flex-col items-center justify-center w-full h-28 md:h-36 bg-gray-800 border-2 border-dashed border-gray-600 rounded-xl cursor-pointer hover:border-orange-500/50 transition"
                           :class="uploading ? 'opacity-50 pointer-events-none' : ''">
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl mb-1">📷</div>
                            <p class="text-gray-400 text-xs md:text-sm">คลิกเพื่อเลือกรูปหลัก</p>
                            <p class="text-gray-600 text-[10px] md:text-xs mt-0.5">JPG, PNG, WebP (สูงสุด 10MB)</p>
                        </div>
                        <input type="file" accept="image/*" class="hidden"
                               @change="if ($event.target.files[0]) uploadImage($event.target.files[0], 'primary')">
                    </label>
                @endif
                <template x-if="uploading"><p class="text-orange-400 text-[10px] md:text-xs mt-1">⏳ กำลังอัพโหลด...</p></template>
                <template x-if="uploadError"><p class="text-red-400 text-[10px] md:text-xs mt-1" x-text="uploadError"></p></template>
            </div>

            {{-- Gallery Images Upload --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1 md:mb-2">🖼️ รูปเพิ่มเติม (Gallery) <span class="text-gray-600 text-[10px] md:text-xs">— อัพโหลดทีละรูป</span></label>

                @if(count($galleryImages) > 0)
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-2">
                    @foreach($galleryImages as $index => $imgUrl)
                    <div class="relative rounded-lg overflow-hidden aspect-square bg-gray-800">
                        <img src="{{ image_url($imgUrl) }}" class="w-full h-full object-cover" alt="">
                        <button type="button" wire:click="removeGalleryImage({{ $index }})"
                                class="absolute top-1 right-1 w-5 h-5 bg-red-500 hover:bg-red-400 text-white rounded-full flex items-center justify-center text-[10px] transition">✕</button>
                    </div>
                    @endforeach
                </div>
                @endif

                <label class="flex items-center justify-center w-full h-16 md:h-20 bg-gray-800 border-2 border-dashed border-gray-600 rounded-xl cursor-pointer hover:border-orange-500/50 transition"
                       :class="uploading ? 'opacity-50 pointer-events-none' : ''">
                    <div class="text-center">
                        <p class="text-gray-400 text-xs md:text-sm">＋ เพิ่มรูป Gallery</p>
                        <p class="text-gray-600 text-[10px] md:text-xs">เลือกรูปทีละรูป</p>
                    </div>
                    <input type="file" accept="image/*" class="hidden"
                           @change="if ($event.target.files[0]) { uploadImage($event.target.files[0], 'gallery'); $event.target.value = '' }">
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                <div>
                    <label class="block text-gray-400 text-xs md:text-sm mb-1">จังหวัด *</label>
                    <select id="province-select" wire:model="provinceId"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-gray-300 focus:outline-none focus:border-orange-500">
                        <option value="">-- เลือกจังหวัด --</option>
                        @foreach($provinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name_th }}</option>
                        @endforeach
                    </select>
                    @error('provinceId')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-xs md:text-sm mb-1">ประเภทงาน *</label>
                    <select wire:model="workTypeId"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-gray-300 focus:outline-none focus:border-orange-500">
                        <option value="">-- เลือกประเภท --</option>
                        @foreach($workTypes as $wt)
                            <option value="{{ $wt->id }}">{{ $wt->title }}</option>
                        @endforeach
                    </select>
                    @error('workTypeId')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Location Picker --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1 md:mb-2">พิกัดสถานที่ทำงาน (ละติจูด/ลองจิจูด)</label>
                <div x-data="{
                    loadingAddress: false,
                    async handleLocationPicked(e) {
                        const lat = e.detail.lat;
                        const lng = e.detail.lng;
                        
                        @this.set('latitude', lat);
                        @this.set('longitude', lng);
                        
                        this.loadingAddress = true;
                        try {
                            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&accept-language=th`);
                            const data = await res.json();
                            
                            if (data && data.address) {
                                // Extract province name
                                let provinceName = data.address.state || data.address.province || data.address.city || '';
                                
                                // Map string 'จ.' to match our DB ('กรุงเทพมหานคร' or 'เชียงใหม่')
                                provinceName = provinceName.replace('จังหวัด', '').trim();
                                
                                if (provinceName) {
                                    // Let Livewire find the matching ID on backend to be safe, 
                                    // but we can also just find it in the DOM select options
                                    const selectEl = document.getElementById('province-select');
                                    if (selectEl) {
                                        for (let i = 0; i < selectEl.options.length; i++) {
                                            const optionText = selectEl.options[i].text;
                                            if (optionText.includes(provinceName) || provinceName.includes(optionText)) {
                                                @this.set('provinceId', selectEl.options[i].value);
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (err) {
                            console.error('Failed to reverse geocode', err);
                        } finally {
                            this.loadingAddress = false;
                        }
                    }
                }" @location-picked.window="handleLocationPicked">
                    
                    <x-location-picker 
                        wire:model.lat="latitude" 
                        wire:model.lng="longitude" 
                        label="📍 เปิดแผนที่เพื่อระบุตำแหน่ง" 
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2 md:py-2.5 text-sm md:text-base text-orange-400 focus:outline-none hover:bg-gray-700 transition font-semibold" />
                        
                    <div class="mt-1 md:mt-2 text-[10px] md:text-xs text-gray-500 font-mono flex items-center gap-2">
                        <span>พิกัดที่เลือก: <span x-text="$wire.latitude || '-'"></span>, <span x-text="$wire.longitude || '-'"></span></span>
                        <span x-show="loadingAddress" class="text-orange-400">⏳ กำลังค้นหา...</span>
                    </div>
                </div>
                @error('latitude')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Categories --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1 md:mb-2">หมวดหมู่</label>
                <div class="flex flex-wrap gap-1.5 md:gap-2">
                    @foreach($categories as $cat)
                        <label class="inline-flex items-center gap-1 md:gap-1.5 px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-[10px] md:text-xs cursor-pointer transition
                            {{ in_array($cat->id, $selectedCategories) ? 'bg-orange-500/20 text-orange-400 ring-1 ring-orange-500/40' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                            <input wire:model="selectedCategories" type="checkbox" value="{{ $cat->id }}" class="hidden">
                            {{ $cat->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit"
                :disabled="uploading"
                class="w-full py-2.5 md:py-3 bg-orange-500 hover:bg-orange-400 disabled:opacity-50 text-white font-bold rounded-xl transition text-base md:text-lg">
            <span wire:loading.remove wire:target="submit">✅ สร้างงาน</span>
            <span wire:loading wire:target="submit">⏳ กำลังสร้าง...</span>
        </button>
    </form>
    @endif

</div>
