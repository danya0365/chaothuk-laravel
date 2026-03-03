<div class="max-w-2xl mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold text-white mb-6">🎨 เพิ่มผลงานใหม่</h1>

    <form wire:submit="save" class="space-y-4">
        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">ชื่อผลงาน *</label>
            <input type="text" wire:model="title" placeholder="เช่น ขนส่งสินค้าข้ามจังหวัด"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
            @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Work Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">ประเภทงาน</label>
            <select wire:model="workTypeId"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
                <option value="">-- เลือกประเภท --</option>
                @foreach($workTypes as $wt)
                    <option value="{{ $wt['id'] }}">{{ $wt['title'] }}</option>
                @endforeach
            </select>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">รายละเอียด</label>
            <textarea wire:model="description" rows="4" placeholder="อธิบายผลงาน..."
                      class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 resize-none transition"></textarea>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="px-6 py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg transition text-sm">
                💾 บันทึก
            </button>
            <a href="{{ route('frontend.portfolios') }}"
               class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition text-sm">
                ยกเลิก
            </a>
        </div>
    </form>

</div>
