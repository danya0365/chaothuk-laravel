<div class="max-w-3xl mx-auto px-4 py-6"
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

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('frontend.works') }}?tab=my" class="text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-2xl font-bold text-white">📦 สร้างงานใหม่</h1>
    </div>

    <form wire:submit="submit" class="space-y-4">
        <div class="bg-gray-900 rounded-2xl p-5 space-y-4">

            <div>
                <label class="block text-gray-400 text-sm mb-1">ชื่องาน *</label>
                <input wire:model="title" type="text" placeholder="เช่น รถกระบะรับจ้างขนส่ง"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">รหัสงาน (Code) * <span class="text-gray-600 text-xs">เช่น ทะเบียนรถ หรือรหัสเฉพาะ</span></label>
                <input wire:model="code" type="text" placeholder="เช่น กก-1234 หรือ ABC123"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500 uppercase">
                @error('code')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">รายละเอียด *</label>
                <textarea wire:model="description" rows="4" placeholder="อธิบายบริการของคุณ..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500 resize-none"></textarea>
                @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">ราคา (บาท) *</label>
                <input wire:model="price" type="number" step="0.01" placeholder="0.00"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                @error('price')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Primary Image Upload --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">📸 รูปหลัก</label>
                @if($primaryImage)
                    <div class="relative rounded-xl overflow-hidden mb-2">
                        <img src="{{ $primaryImage }}" class="w-full h-48 object-cover rounded-xl" alt="preview">
                        <button type="button" wire:click="removePrimaryImage"
                                class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-400 text-white rounded-full flex items-center justify-center text-sm transition">✕</button>
                    </div>
                @else
                    <label class="flex flex-col items-center justify-center w-full h-36 bg-gray-800 border-2 border-dashed border-gray-600 rounded-xl cursor-pointer hover:border-orange-500/50 transition"
                           :class="uploading ? 'opacity-50 pointer-events-none' : ''">
                        <div class="text-center">
                            <div class="text-3xl mb-1">📷</div>
                            <p class="text-gray-400 text-sm">คลิกเพื่อเลือกรูปหลัก</p>
                            <p class="text-gray-600 text-xs mt-0.5">JPG, PNG, WebP (สูงสุด 10MB)</p>
                        </div>
                        <input type="file" accept="image/*" class="hidden"
                               @change="if ($event.target.files[0]) uploadImage($event.target.files[0], 'primary')">
                    </label>
                @endif
                <template x-if="uploading"><p class="text-orange-400 text-xs mt-1">⏳ กำลังอัพโหลด...</p></template>
                <template x-if="uploadError"><p class="text-red-400 text-xs mt-1" x-text="uploadError"></p></template>
            </div>

            {{-- Gallery Images Upload --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">🖼️ รูปเพิ่มเติม (Gallery) <span class="text-gray-600 text-xs">— อัพโหลดทีละรูป</span></label>

                @if(count($galleryImages) > 0)
                <div class="grid grid-cols-4 gap-2 mb-2">
                    @foreach($galleryImages as $index => $imgUrl)
                    <div class="relative rounded-lg overflow-hidden aspect-square bg-gray-800">
                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover" alt="">
                        <button type="button" wire:click="removeGalleryImage({{ $index }})"
                                class="absolute top-1 right-1 w-5 h-5 bg-red-500 hover:bg-red-400 text-white rounded-full flex items-center justify-center text-[10px] transition">✕</button>
                    </div>
                    @endforeach
                </div>
                @endif

                <label class="flex items-center justify-center w-full h-20 bg-gray-800 border-2 border-dashed border-gray-600 rounded-xl cursor-pointer hover:border-orange-500/50 transition"
                       :class="uploading ? 'opacity-50 pointer-events-none' : ''">
                    <div class="text-center">
                        <p class="text-gray-400 text-sm">＋ เพิ่มรูป Gallery</p>
                        <p class="text-gray-600 text-xs">เลือกรูปทีละรูป</p>
                    </div>
                    <input type="file" accept="image/*" class="hidden"
                           @change="if ($event.target.files[0]) { uploadImage($event.target.files[0], 'gallery'); $event.target.value = '' }">
                </label>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">จังหวัด *</label>
                    <select wire:model="provinceId"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-300 focus:outline-none focus:border-orange-500">
                        <option value="">-- เลือกจังหวัด --</option>
                        @foreach($provinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name_th }}</option>
                        @endforeach
                    </select>
                    @error('provinceId')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">ประเภทงาน *</label>
                    <select wire:model="workTypeId"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-gray-300 focus:outline-none focus:border-orange-500">
                        <option value="">-- เลือกประเภท --</option>
                        @foreach($workTypes as $wt)
                            <option value="{{ $wt->id }}">{{ $wt->title }}</option>
                        @endforeach
                    </select>
                    @error('workTypeId')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">ละติจูด</label>
                    <input wire:model="latitude" type="number" step="0.0000001" placeholder="13.7563"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">ลองจิจูด</label>
                    <input wire:model="longitude" type="number" step="0.0000001" placeholder="100.5018"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                </div>
            </div>

            {{-- Categories --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">หมวดหมู่</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs cursor-pointer transition
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
                class="w-full py-3 bg-orange-500 hover:bg-orange-400 disabled:opacity-50 text-white font-bold rounded-xl transition text-lg">
            <span wire:loading.remove wire:target="submit">✅ สร้างงาน</span>
            <span wire:loading wire:target="submit">⏳ กำลังสร้าง...</span>
        </button>
    </form>

</div>
