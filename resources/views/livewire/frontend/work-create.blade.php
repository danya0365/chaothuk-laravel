<div class="max-w-3xl mx-auto px-4 py-6">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('frontend.works') }}?tab=my" class="text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-2xl font-bold text-white">📦 สร้างงานใหม่</h1>
    </div>

    @if($successMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl px-4 py-3 text-sm mb-4">
            {{ $successMessage }}
        </div>
    @endif

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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">ราคา (บาท) *</label>
                    <input wire:model="price" type="number" step="0.01" placeholder="0.00"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                    @error('price')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">URL รูปภาพหลัก</label>
                    <input wire:model="primaryImage" type="url" placeholder="https://..."
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                </div>
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
                            <option value="{{ $wt->id }}">{{ $wt->name }}</option>
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
                class="w-full py-3 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl transition text-lg">
            ✅ สร้างงาน
        </button>
    </form>

</div>
