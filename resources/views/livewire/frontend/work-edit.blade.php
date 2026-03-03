<div class="max-w-3xl mx-auto px-4 py-6">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('frontend.works.show', $id) }}"
           class="text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-xl font-bold text-white">✏️ แก้ไขงาน</h1>
    </div>

    @if($successMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl px-4 py-3 text-sm mb-4">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-5">

        {{-- Title --}}
        <div>
            <label class="block text-gray-300 text-sm font-semibold mb-1">ชื่องาน *</label>
            <input wire:model="title" type="text"
                   class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition"
                   placeholder="เช่น รถกะบะรับจ้างขนส่ง">
            @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Code --}}
        <div>
            <label class="block text-gray-300 text-sm font-semibold mb-1">รหัสงาน (Code) * <span class="text-gray-500 text-xs font-normal">เช่น ทะเบียนรถ</span></label>
            <input wire:model="code" type="text"
                   class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition uppercase"
                   placeholder="เช่น กก-1234">
            @error('code')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-gray-300 text-sm font-semibold mb-1">รายละเอียด *</label>
            <textarea wire:model="description" rows="5"
                      class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition resize-none"
                      placeholder="อธิบายงานให้ละเอียด..."></textarea>
            @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Price + Status --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-1">ราคา (฿) *</label>
                <input wire:model="price" type="number" step="0.01" min="0"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition">
                @error('price')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-1">สถานะ</label>
                <select wire:model="workStatus"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-gray-300 focus:outline-none focus:border-orange-500 transition">
                    <option value="">เลือกสถานะ</option>
                    <option value="stand-by">🟢 พร้อมรับงาน</option>
                    <option value="busy">🟡 ไม่ว่าง</option>
                    <option value="close">🔴 ปิดงาน</option>
                </select>
            </div>
        </div>

        {{-- Province + Work Type --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-1">จังหวัด *</label>
                <select wire:model="provinceId"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-gray-300 focus:outline-none focus:border-orange-500 transition">
                    <option value="">เลือกจังหวัด</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->id }}">{{ $p->name_th }}</option>
                    @endforeach
                </select>
                @error('provinceId')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-1">ประเภทงาน *</label>
                <select wire:model="workTypeId"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-gray-300 focus:outline-none focus:border-orange-500 transition">
                    <option value="">เลือกประเภท</option>
                    @foreach($workTypes as $wt)
                        <option value="{{ $wt->id }}">{{ $wt->name }}</option>
                    @endforeach
                </select>
                @error('workTypeId')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Image URL --}}
        <div>
            <label class="block text-gray-300 text-sm font-semibold mb-1">URL รูปภาพหลัก</label>
            <input wire:model="primaryImage" type="url"
                   class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition"
                   placeholder="https://...">
            @if($primaryImage)
                <img src="{{ $primaryImage }}" class="mt-2 w-full h-40 object-cover rounded-xl" alt="preview">
            @endif
        </div>

        {{-- Lat/Lng --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-1">Latitude</label>
                <input wire:model="latitude" type="number" step="0.000001"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition"
                       placeholder="13.7563">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-semibold mb-1">Longitude</label>
                <input wire:model="longitude" type="number" step="0.000001"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500 transition"
                       placeholder="100.5018">
            </div>
        </div>

        {{-- Categories --}}
        <div>
            <label class="block text-gray-300 text-sm font-semibold mb-2">หมวดหมู่</label>
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $cat)
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model="selectedCategories" value="{{ $cat->id }}" class="sr-only peer">
                        <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-semibold transition
                            peer-checked:bg-orange-500 peer-checked:text-white
                            bg-gray-800 text-gray-400 hover:bg-gray-700 ring-1 ring-gray-700 peer-checked:ring-orange-500">
                            {{ $cat->name }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="px-8 py-3 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl transition text-sm">
                💾 บันทึกการแก้ไข
            </button>
            <a href="{{ route('frontend.works.show', $id) }}"
               class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-semibold rounded-xl transition text-sm ring-1 ring-gray-700">
                ยกเลิก
            </a>
        </div>

    </form>

</div>
