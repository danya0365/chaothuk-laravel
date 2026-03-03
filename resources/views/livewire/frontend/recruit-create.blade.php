<div class="max-w-3xl mx-auto px-4 py-6">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('frontend.recruits') }}?tab=my" class="text-gray-400 hover:text-white transition">← กลับ</a>
        <h1 class="text-2xl font-bold text-white">👷 สร้างประกาศหางาน</h1>
    </div>

    <form wire:submit="submit" class="space-y-4">
        <div class="bg-gray-900 rounded-2xl p-5 space-y-4">

            <div>
                <label class="block text-gray-400 text-sm mb-1">ชื่อตำแหน่ง *</label>
                <input wire:model="title" type="text" placeholder="เช่น รับสมัครคนขับรถบรรทุก"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">รายละเอียด *</label>
                <textarea wire:model="description" rows="4" placeholder="อธิบายรายละเอียดงาน คุณสมบัติที่ต้องการ..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500 resize-none"></textarea>
                @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">งบประมาณ / เดือน (บาท) *</label>
                    <input wire:model="budget" type="number" step="0.01" placeholder="0.00"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-orange-500">
                    @error('budget')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
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
        </div>

        <button type="submit"
                class="w-full py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition text-lg">
            ✅ สร้างประกาศ
        </button>
    </form>

</div>
