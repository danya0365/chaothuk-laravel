<div class="max-w-2xl mx-auto px-3 sm:px-4 py-4 md:py-6"
     x-data="{
         uploading: false,
         uploadError: '',
         async uploadImage(file) {
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
                     @this.set('images', [...@this.get('images'), url]);
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
        <a href="{{ route('frontend.portfolios') }}" class="text-xs md:text-sm text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-xl md:text-2xl font-bold text-white">🎨 เพิ่มผลงานใหม่</h1>
    </div>

    <form wire:submit="save" class="space-y-3 md:space-y-4">
        <div class="bg-gray-900 rounded-2xl p-4 md:p-5 space-y-3 md:space-y-4">

            {{-- Title --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">ชื่อผลงาน <span class="text-red-400">*</span></label>
                <input wire:model="title" type="text" placeholder="เช่น ขนส่งเฟอร์นิเจอร์ทั้งชุด"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 md:px-4 md:py-2.5 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500 transition">
                @error('title')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Work Type --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">ประเภทงาน <span class="text-red-400">*</span></label>
                <select wire:model="workTypeId"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 md:px-4 md:py-2.5 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500 transition">
                    <option value="">-- เลือกประเภท --</option>
                    @foreach($workTypes as $wt)
                        <option value="{{ $wt['id'] }}">{{ $wt['title'] }}</option>
                    @endforeach
                </select>
                @error('workTypeId')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1">รายละเอียดผลงาน <span class="text-red-400">*</span></label>
                <textarea wire:model="description" rows="5" placeholder="อธิบายผลงาน เช่น ลูกค้าจ้างขนย้ายเฟอร์นิเจอร์จากลาดพร้าวไปนนทบุรี ของไม่เสียหาย ลูกค้าพอใจมาก..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 md:px-4 md:py-2.5 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500 resize-none transition"></textarea>
                @error('description')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Image Gallery Upload --}}
            <div>
                <label class="block text-gray-400 text-xs md:text-sm mb-1.5 md:mb-2">🖼️ รูปภาพผลงาน <span class="text-red-400">*</span> <span class="text-gray-600 text-[10px] md:text-xs">— อัพโหลดทีละรูป</span></label>

                @if(count($images) > 0)
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-2 md:mb-3">
                    @foreach($images as $index => $imgUrl)
                    <div class="relative rounded-xl overflow-hidden aspect-square bg-gray-800 group">
                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover" alt="ผลงาน {{ $index + 1 }}">
                        <button type="button" wire:click="removeImage({{ $index }})"
                                class="absolute top-1 right-1 md:top-1.5 md:right-1.5 w-5 h-5 md:w-6 md:h-6 bg-red-500 hover:bg-red-400 text-white rounded-full flex items-center justify-center text-[10px] md:text-xs opacity-0 group-hover:opacity-100 transition">✕</button>
                        @if($index === 0)
                            <span class="absolute bottom-1 left-1 bg-orange-500/90 text-white text-[8px] md:text-[9px] font-bold px-1 py-0.5 md:px-1.5 md:py-0.5 rounded">ปก</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <label class="flex flex-col items-center justify-center w-full h-24 md:h-28 bg-gray-800 border-2 border-dashed border-gray-600 rounded-xl cursor-pointer hover:border-orange-500/50 transition"
                       :class="uploading ? 'opacity-50 pointer-events-none' : ''">
                    <div class="text-center">
                        <div class="text-xl md:text-2xl mb-0.5">📷</div>
                        <p class="text-gray-400 text-xs md:text-sm">คลิกเพื่อเพิ่มรูปภาพ</p>
                        <p class="text-gray-600 text-[10px] md:text-xs">JPG, PNG, WebP (สูงสุด 10MB)</p>
                    </div>
                    <input type="file" accept="image/*" class="hidden"
                           @change="if ($event.target.files[0]) { uploadImage($event.target.files[0]); $event.target.value = '' }">
                </label>
                <template x-if="uploading"><p class="text-orange-400 text-[10px] md:text-xs mt-1 flex items-center gap-1">⏳ กำลังอัพโหลด...</p></template>
                <template x-if="uploadError"><p class="text-red-400 text-[10px] md:text-xs mt-1" x-text="uploadError"></p></template>
                @error('images')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 md:gap-3 pt-2">
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    class="px-4 py-2 md:px-6 md:py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl transition text-xs md:text-sm">
                <span wire:loading.remove wire:target="save">💾 บันทึกผลงาน</span>
                <span wire:loading wire:target="save">⏳ กำลังบันทึก...</span>
            </button>
            <a href="{{ route('frontend.portfolios') }}"
               class="px-4 py-2 md:px-6 md:py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl transition text-xs md:text-sm ring-1 ring-gray-700">
                ยกเลิก
            </a>
        </div>
    </form>

</div>
