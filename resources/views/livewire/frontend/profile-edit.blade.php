<div class="max-w-2xl mx-auto px-4 py-6"
     x-data="{
         uploading: false,
         uploadProgress: 0,
         uploadError: '',
         dragOver: false,
         showCropModal: false,
         cropImageUrl: '',
         cropperObj: null,

         onFileSelect(file) {
             if (!file) return;
             if (!file.type.startsWith('image/')) {
                 this.uploadError = 'กรุณาเลือกไฟล์รูปภาพเท่านั้น';
                 return;
             }
             if (file.size > 10 * 1024 * 1024) {
                 this.uploadError = 'ไฟล์ต้องมีขนาดไม่เกิน 10MB';
                 return;
             }
             this.uploadError = '';
             const reader = new FileReader();
             reader.onload = (e) => {
                 this.cropImageUrl = e.target.result;
                 this.showCropModal = true;
                 this.$nextTick(() => {
                     if (this.cropperObj) this.cropperObj.destroy();
                     const imgEl = document.getElementById('cropper-image');
                     this.cropperObj = new Cropper(imgEl, {
                         aspectRatio: 1,
                         viewMode: 1,
                         dragMode: 'move',
                         autoCropArea: 1,
                         restore: false,
                         guides: true,
                         center: true,
                         highlight: false,
                         cropBoxMovable: true,
                         cropBoxResizable: true,
                         toggleDragModeOnDblclick: false,
                     });
                 });
             };
             reader.readAsDataURL(file);
         },

         doCropAndUpload() {
             if (!this.cropperObj) return;
             this.cropperObj.getCroppedCanvas({
                 width: 600,
                 height: 600,
                 imageSmoothingEnabled: true,
                 imageSmoothingQuality: 'high',
             }).toBlob((blob) => {
                 this.showCropModal = false;
                 this.cropperObj.destroy();
                 this.cropperObj = null;
                 this.uploadAvatar(blob);
             }, 'image/webp', 0.9);
         },

         zoomCropper(ratio) {
             if (this.cropperObj) this.cropperObj.zoom(ratio);
         },

         async uploadAvatar(blob) {
             this.uploading = true;
             this.uploadProgress = 0;
             this.uploadError = '';

             const formData = new FormData();
             formData.append('avatar', blob, 'avatar.webp');

             try {
                 const xhr = new XMLHttpRequest();
                 xhr.upload.addEventListener('progress', (e) => {
                     if (e.lengthComputable) this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                 });

                 const result = await new Promise((resolve, reject) => {
                     xhr.onload = () => {
                         try { resolve(JSON.parse(xhr.responseText)); }
                         catch { reject(new Error('Invalid response')); }
                     };
                     xhr.onerror = () => reject(new Error('Network error'));
                     xhr.open('POST', '/api/upload/avatar');
                     xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name=csrf-token]')?.content || '');
                     xhr.send(formData);
                 });

                 if (result.status && result.data && result.data.avatar) {
                     const url = result.data.avatar;
                     @this.set('profileImage', url);
                     this.uploadProgress = 100;
                 } else {
                     this.uploadError = result.message || 'อัพโหลดไม่สำเร็จ';
                 }
             } catch (e) {
                 this.uploadError = 'เกิดข้อผิดพลาดในการอัพโหลด';
             }
             setTimeout(() => { this.uploading = false; this.uploadProgress = 0; }, 600);
         },
         handleDrop(e) {
             this.dragOver = false;
             const file = e.dataTransfer?.files?.[0];
             if (file) this.onFileSelect(file);
         }
     }">

    @push('styles')
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
        .cropper-view-box {
            outline: 2px solid #f97316;
            outline-color: rgba(249, 115, 22, 0.75);
        }
        #cropper-image {
            display: block;
            max-width: 100%;
        }
    </style>
    @endpush

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('frontend.profile') }}" class="text-gray-400 hover:text-white transition text-sm">← กลับ</a>
        <h1 class="text-2xl font-black text-white">แก้ไขโปรไฟล์</h1>
    </div>

    <form wire:submit="save" class="space-y-6">

        {{-- ═══════════════════════════════════════════════════════════════════
             AVATAR UPLOAD — World-class design
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
            <label class="block text-sm font-semibold text-white mb-4">รูปโปรไฟล์</label>

            <div class="flex flex-col sm:flex-row items-center gap-6">
                {{-- Avatar preview + upload zone --}}
                <div class="relative group"
                     @dragover.prevent="dragOver = true"
                     @dragleave.prevent="dragOver = false"
                     @drop.prevent="handleDrop($event)">

                    {{-- Circular avatar --}}
                    <div class="relative w-32 h-32 rounded-full overflow-hidden ring-4 transition-all duration-300"
                         :class="dragOver ? 'ring-orange-500 scale-105 shadow-2xl shadow-orange-500/30' : 'ring-gray-700 group-hover:ring-orange-500/50'">
                        @if($profileImage)
                            <img src="{{ $profileImage }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                                <span class="text-white text-4xl font-black">{{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                        @endif

                        {{-- Hover overlay --}}
                        <label class="absolute inset-0 flex flex-col items-center justify-center bg-black/70 cursor-pointer transition-opacity duration-200"
                               :class="uploading ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                            <template x-if="!uploading">
                                <div class="text-center">
                                    <svg class="w-6 h-6 text-white mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-white text-xs font-semibold">เปลี่ยนรูป</span>
                                </div>
                            </template>
                            <template x-if="uploading">
                                <div class="text-center">
                                    {{-- Circular progress --}}
                                    <svg class="w-12 h-12 mx-auto" viewBox="0 0 48 48">
                                        <circle cx="24" cy="24" r="20" fill="none" stroke="#374151" stroke-width="3"/>
                                        <circle cx="24" cy="24" r="20" fill="none" stroke="#f97316" stroke-width="3"
                                                stroke-linecap="round"
                                                :stroke-dasharray="125.6"
                                                :stroke-dashoffset="125.6 - (125.6 * uploadProgress / 100)"
                                                transform="rotate(-90 24 24)"
                                                class="transition-all duration-300"/>
                                    </svg>
                                    <span class="text-orange-400 text-xs font-bold mt-1 block" x-text="uploadProgress + '%'"></span>
                                </div>
                            </template>
                            <input type="file" accept="image/jpeg,image/png,image/webp" class="hidden"
                                   @change="if ($event.target.files[0]) { onFileSelect($event.target.files[0]); $event.target.value = '' }">
                        </label>
                    </div>

                    {{-- Status badge --}}
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-lg transition-all duration-300"
                         :class="uploading ? 'bg-orange-500 animate-pulse' : 'bg-gray-700 group-hover:bg-orange-500'">
                        <template x-if="!uploading"><span>📷</span></template>
                        <template x-if="uploading"><span class="animate-spin">⏳</span></template>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-gray-300 text-sm font-medium mb-1">อัพโหลดรูปโปรไฟล์ใหม่</p>
                    <p class="text-gray-500 text-xs leading-relaxed mb-3">
                        คลิกที่รูปหรือลากไฟล์มาวาง<br>
                        รองรับ JPG, PNG, WebP (สูงสุด 10MB)
                    </p>
                    @if($profileImage)
                    <button type="button" wire:click="removeAvatar"
                            class="text-xs text-red-400 hover:text-red-300 border border-red-500/30 rounded-lg px-3 py-1.5 hover:bg-red-500/10 transition">
                        🗑️ ลบรูปโปรไฟล์
                    </button>
                    @endif
                </div>
            </div>

            {{-- Error --}}
            <template x-if="uploadError">
                <div class="mt-3 bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-lg px-3 py-2" x-text="uploadError"></div>
            </template>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             PERSONAL INFO
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 space-y-5">
            <label class="block text-sm font-semibold text-white">ข้อมูลส่วนตัว</label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-500 text-xs mb-1.5 uppercase tracking-wide">ชื่อจริง <span class="text-red-400">*</span></label>
                    <input wire:model="firstName" type="text" placeholder="ชื่อ"
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition placeholder-gray-600">
                    @error('firstName')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-500 text-xs mb-1.5 uppercase tracking-wide">นามสกุล <span class="text-red-400">*</span></label>
                    <input wire:model="lastName" type="text" placeholder="นามสกุล"
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition placeholder-gray-600">
                    @error('lastName')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-500 text-xs mb-1.5 uppercase tracking-wide">ชื่อที่แสดง <span class="text-red-400">*</span></label>
                <input wire:model="name" type="text" placeholder="ชื่อที่จะแสดงในระบบ"
                       class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition placeholder-gray-600">
                <p class="text-gray-600 text-[11px] mt-1">ชื่อนี้จะแสดงในหน้างาน โปรไฟล์ และรีวิว</p>
                @error('name')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             CONTACT INFO
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 space-y-5">
            <label class="block text-sm font-semibold text-white">ข้อมูลติดต่อ</label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-500 text-xs mb-1.5 uppercase tracking-wide">📱 เบอร์โทรศัพท์</label>
                    <input wire:model="mobilePhone" type="tel" placeholder="0xx-xxx-xxxx"
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition placeholder-gray-600">
                    @error('mobilePhone')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-500 text-xs mb-1.5 uppercase tracking-wide">📍 ที่อยู่ / พื้นที่</label>
                    <input wire:model="location" type="text" placeholder="เช่น กรุงเทพฯ, เชียงใหม่"
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition placeholder-gray-600">
                    @error('location')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-500 text-xs mb-1.5 uppercase tracking-wide">✉️ อีเมล</label>
                <input type="email" value="{{ auth()->user()->email }}" disabled
                       class="w-full bg-gray-800/30 border border-gray-700/50 rounded-xl px-4 py-3 text-gray-500 text-sm cursor-not-allowed">
                <p class="text-gray-600 text-[11px] mt-1">อีเมลไม่สามารถเปลี่ยนได้จากหน้านี้</p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             BIO
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 space-y-4">
            <label class="block text-sm font-semibold text-white">แนะนำตัว</label>

            <div>
                <textarea wire:model="biography" rows="4" placeholder="เล่าเกี่ยวกับตัวคุณ ทักษะ ประสบการณ์ สิ่งที่ชำนาญ..."
                          class="w-full bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 resize-none transition placeholder-gray-600"
                          x-data x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"></textarea>
                <div class="flex justify-between mt-1">
                    <p class="text-gray-600 text-[11px]">แนะนำตัวเองให้ลูกค้ารู้จักคุณ</p>
                    <p class="text-gray-600 text-[11px]"><span x-data x-text="$wire.biography?.length || 0"></span>/1000</p>
                </div>
                @error('biography')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             ACTIONS
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="flex items-center gap-3 sticky bottom-4 z-10">
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    class="flex-1 sm:flex-none px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-orange-500/20 hover:shadow-orange-500/30">
                <span wire:loading.remove wire:target="save">💾 บันทึกการเปลี่ยนแปลง</span>
                <span wire:loading wire:target="save">⏳ กำลังบันทึก...</span>
            </button>
            <a href="{{ route('frontend.profile') }}"
               class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl transition text-sm ring-1 ring-gray-700 hover:ring-gray-600">
                ยกเลิก
            </a>
        </div>

    </form>

    {{-- ═══════════════════════════════════════════════════════════════════
         CROP MODAL
    ═══════════════════════════════════════════════════════════════════ --}}
    <div x-show="showCropModal" style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        {{-- Modal --}}
        <div class="relative bg-gray-900 border border-gray-800 rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col"
             style="max-height: 90vh;"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">จัดการรูปโปรไฟล์</h3>
                <button type="button" @click="showCropModal = false; if(cropperObj) cropperObj.destroy();"
                        class="text-gray-400 hover:text-white transition">✕</button>
            </div>
            
            {{-- Cropper Area --}}
            <div class="flex-1 min-h-[300px] h-[50vh] bg-black">
                <img id="cropper-image" :src="cropImageUrl" class="block max-w-full">
            </div>

            {{-- Controls --}}
            <div class="p-6 bg-gray-900 border-t border-gray-800">
                <div class="flex items-center justify-center gap-4 mb-6">
                    <button type="button" @click="zoomCropper(-0.1)"
                            class="w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white flex items-center justify-center transition ring-1 ring-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
                        </svg>
                    </button>
                    <button type="button" @click="zoomCropper(0.1)"
                            class="w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white flex items-center justify-center transition ring-1 ring-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </button>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="showCropModal = false; if(cropperObj) cropperObj.destroy();"
                            class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-xl transition">
                        ยกเลิก
                    </button>
                    <button type="button" @click="doCropAndUpload"
                            class="flex-1 py-3 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl transition shadow-lg shadow-orange-500/20">
                        ครอบตัด & อัพโหลด
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
