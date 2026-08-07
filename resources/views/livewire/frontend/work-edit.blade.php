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
        <a href="{{ route('frontend.works.show', $id) }}"
           class="text-xs md:text-sm text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-lg md:text-xl font-bold text-white">✏️ แก้ไขงาน</h1>
    </div>

    @if($successMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm mb-3 md:mb-4">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-4 md:space-y-5">

        {{-- Title --}}
        <div>
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">ชื่องาน *</label>
            <input wire:model="title" type="text"
                   class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-white focus:outline-none focus:border-orange-500 transition"
                   placeholder="เช่น รถกะบะรับจ้างขนส่ง">
            @error('title')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Code --}}
        <div>
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">รหัสงาน (Code) * <span class="text-gray-500 text-[10px] md:text-xs font-normal">เช่น ทะเบียนรถ</span></label>
            <input wire:model="code" type="text"
                   class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-white focus:outline-none focus:border-orange-500 transition uppercase"
                   placeholder="เช่น กก-1234">
            @error('code')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">รายละเอียด *</label>
            <textarea wire:model="description" rows="5"
                      class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-white focus:outline-none focus:border-orange-500 transition resize-none"
                      placeholder="อธิบายงานให้ละเอียด..."></textarea>
            @error('description')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Price + Status --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
            <div>
                <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">ราคา (฿) *</label>
                <input wire:model="price" type="number" step="0.01" min="0"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-white focus:outline-none focus:border-orange-500 transition">
                @error('price')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">สถานะ</label>
                <select wire:model="workStatus"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-gray-300 focus:outline-none focus:border-orange-500 transition">
                    <option value="">เลือกสถานะ</option>
                    <option value="stand-by">🟢 พร้อมรับงาน</option>
                    <option value="busy">🟡 ไม่ว่าง</option>
                    <option value="close">🔴 ปิดงาน</option>
                </select>
            </div>
        </div>

        {{-- Primary Image Upload --}}
        <div>
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1 md:mb-2">📸 รูปหลัก</label>
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
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1 md:mb-2">🖼️ รูปเพิ่มเติม (Gallery)</label>

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
                    <p class="text-gray-600 text-[10px] md:text-xs">อัพโหลดทีละรูป</p>
                </div>
                <input type="file" accept="image/*" class="hidden"
                       @change="if ($event.target.files[0]) { uploadImage($event.target.files[0], 'gallery'); $event.target.value = '' }">
            </label>
        </div>

        {{-- Province + Work Type --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
            <div>
                <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">จังหวัด *</label>
                <select id="province-select" wire:model="provinceId"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-gray-300 focus:outline-none focus:border-orange-500 transition">
                    <option value="">เลือกจังหวัด</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->id }}">{{ $p->name_th }}</option>
                    @endforeach
                </select>
                @error('provinceId')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1">ประเภทงาน *</label>
                <select wire:model="workTypeId"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-gray-300 focus:outline-none focus:border-orange-500 transition">
                    <option value="">เลือกประเภท</option>
                    @foreach($workTypes as $wt)
                        <option value="{{ $wt->id }}">{{ $wt->title }}</option>
                    @endforeach
                </select>
                @error('workTypeId')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Location Picker --}}
        <div>
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1 md:mb-2">พิกัดสถานที่ทำงาน (Latitude / Longitude)</label>
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
                                let provinceName = data.address.state || data.address.province || data.address.city || '';
                                provinceName = provinceName.replace('จังหวัด', '').trim();
                                
                                if (provinceName) {
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
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base text-orange-400 focus:outline-none hover:bg-gray-700 transition font-semibold" />
                    <div class="mt-1 md:mt-2 text-[10px] md:text-xs text-gray-500 font-mono flex items-center gap-2">
                        <span>พิกัดที่เลือก: <span x-text="$wire.latitude || '-'"></span>, <span x-text="$wire.longitude || '-'"></span></span>
                        <span x-show="loadingAddress" class="text-orange-400">⏳ กำลังค้นหา...</span>
                    </div>
                </div>
            @error('latitude')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Categories --}}
        <div>
            <label class="block text-gray-300 text-xs md:text-sm font-semibold mb-1 md:mb-2">หมวดหมู่</label>
            <div class="flex flex-wrap gap-1.5 md:gap-2">
                @foreach($categories as $cat)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model="selectedCategories" value="{{ $cat->id }}" class="sr-only peer">
                        <span class="inline-block px-2.5 md:px-3 py-1 md:py-1.5 rounded-lg text-[10px] md:text-xs font-semibold transition select-none
                            peer-checked:bg-orange-500 peer-checked:text-white
                            bg-gray-800 text-gray-400 hover:bg-gray-700 ring-1 ring-gray-700 peer-checked:ring-orange-500">
                            {{ $cat->name }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-2 md:gap-3 pt-2">
            <button type="submit"
                    :disabled="uploading"
                    class="flex-1 md:flex-none px-4 md:px-8 py-2.5 md:py-3 bg-orange-500 hover:bg-orange-400 disabled:opacity-50 text-white font-bold rounded-xl transition text-xs md:text-sm shadow-lg shadow-orange-500/20">
                <span wire:loading.remove wire:target="save">💾 บันทึก</span>
                <span wire:loading wire:target="save">⏳ กำลังบันทึก...</span>
            </button>
            <a href="{{ route('frontend.works.show', $id) }}"
               class="flex-1 md:flex-none text-center px-4 md:px-6 py-2.5 md:py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-semibold rounded-xl transition text-xs md:text-sm ring-1 ring-gray-700">
                ยกเลิก
            </a>
        </div>

    </form>

</div>
