<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-white flex items-center gap-2">📥 ออเดอร์ลูกค้า (My Orders)</h1>
        <p class="text-gray-400 mt-1">จัดการคำสั่งจองและงานที่กำลังดำเนินการทั้งหมดจากลูกค้าของคุณ</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div wire:click="switchTab('pending')" class="cursor-pointer bg-gray-900 border {{ $tab === 'pending' ? 'border-orange-500 bg-orange-500/10' : 'border-gray-800 hover:border-gray-700' }} rounded-xl p-4 transition">
            <p class="text-gray-500 text-xs font-bold uppercase mb-1">รอรับงาน / คิวใหม่</p>
            <div class="text-2xl font-black {{ $tab === 'pending' ? 'text-orange-400' : 'text-white' }}">{{ $stats['pending'] }}</div>
        </div>
        <div wire:click="switchTab('active')" class="cursor-pointer bg-gray-900 border {{ $tab === 'active' ? 'border-blue-500 bg-blue-500/10' : 'border-gray-800 hover:border-gray-700' }} rounded-xl p-4 transition">
            <p class="text-gray-500 text-xs font-bold uppercase mb-1">กำลังดำเนินการ</p>
            <div class="text-2xl font-black {{ $tab === 'active' ? 'text-blue-400' : 'text-white' }}">{{ $stats['active'] }}</div>
        </div>
        <div wire:click="switchTab('completed')" class="cursor-pointer bg-gray-900 border {{ $tab === 'completed' ? 'border-green-500 bg-green-500/10' : 'border-gray-800 hover:border-gray-700' }} rounded-xl p-4 transition">
            <p class="text-gray-500 text-xs font-bold uppercase mb-1">ส่งงานสำเร็จ</p>
            <div class="text-2xl font-black {{ $tab === 'completed' ? 'text-green-400' : 'text-white' }}">{{ $stats['completed'] }}</div>
        </div>
        <div wire:click="switchTab('cancelled')" class="cursor-pointer bg-gray-900 border {{ $tab === 'cancelled' ? 'border-red-500 bg-red-500/10' : 'border-gray-800 hover:border-gray-700' }} rounded-xl p-4 transition">
            <p class="text-gray-500 text-xs font-bold uppercase mb-1">ยกเลิกแล้ว</p>
            <div class="text-2xl font-black {{ $tab === 'cancelled' ? 'text-red-400' : 'text-white' }}">{{ $stats['cancelled'] }}</div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Content List --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
        @if($items->isEmpty())
            <div class="p-16 text-center">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-bold text-white mb-2">ไม่มีข้อมูลในหมวดหมู่นี้</h3>
                <p class="text-gray-500 text-sm">เมื่อมีลูกค้ากดจองงานบริการของคุณ ออเดอร์จะมาแสดงอยู่ที่นี่</p>
            </div>
        @else
            <div class="divide-y divide-gray-800">
                @foreach($items as $item)
                    @if($tab === 'pending')
                        {{-- Booking Item Display --}}
                        <div class="p-5 hover:bg-gray-800/30 transition flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-800 flex-shrink-0">
                                <img src="{{ $item->author?->getAvatar() }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-white">{{ $item->author?->name ?? 'User' }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $item->booking_status === 'waiting-to-confirm' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-green-500/20 text-green-400' }}">
                                        {{ $item->booking_status === 'waiting-to-confirm' ? 'รอคุณตอบรับ' : 'ยืนยันแล้ว รอเริ่มงาน' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-400 mb-1">
                                    สนใจงาน: <a href="{{ route('frontend.works.show', $item->work_id) }}" class="text-orange-400 hover:underline">{{ $item->work->title }}</a>
                                </div>
                                @if($item->customer_message)
                                    <div class="text-xs text-gray-500 bg-gray-800/50 p-2 rounded truncate">💬 "{{ $item->customer_message }}"</div>
                                @endif
                                <div class="text-xs text-gray-500 mt-2">
                                    ติดต่อ: {{ $item->mobile_phone ?? '-' }} • 📅 นัดหมาย: {{ $item->booking_date ? \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2 w-full md:w-auto mt-4 md:mt-0">
                                @if($item->booking_status === 'waiting-to-confirm')
                                    <button wire:click="confirmBooking({{ $item->id }})" class="flex-1 md:flex-none px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-medium text-sm rounded-lg transition">
                                        ยอมรับ
                                    </button>
                                    <button wire:click="cancelBooking({{ $item->id }})" wire:confirm="ปฏิเสธงานนี้?" class="flex-1 md:flex-none px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-medium text-sm rounded-lg transition">
                                        ปฏิเสธ
                                    </button>
                                @elseif($item->booking_status === 'confirm')
                                    <button wire:click="startSession({{ $item->id }})" wire:confirm="เริ่มจับเวลาการทำงานและเปิดห้องงาน (Session) เลยหรือไม่?" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-lg transition shadow-lg shadow-blue-500/20">
                                        ▶️ เริ่มทำงาน (สร้าง Session)
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Session Item Display --}}
                        <div class="p-5 hover:bg-gray-800/30 transition flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-800 flex-shrink-0">
                                <img src="{{ $item->customer?->getAvatar() }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-white">{{ $item->customer?->name ?? 'User' }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full font-bold
                                        {{ match($item->status) {
                                            'active'    => 'bg-blue-500/20 text-blue-400',
                                            'paused'    => 'bg-yellow-500/20 text-yellow-400',
                                            'completed' => 'bg-green-500/20 text-green-400',
                                            'cancelled' => 'bg-red-500/20 text-red-400',
                                            default     => 'bg-gray-700 text-gray-400',
                                        } }}">
                                        {{ match($item->status) {
                                            'active'    => 'กำลังทำงาน',
                                            'paused'    => 'หยุดชั่วคราว',
                                            'completed' => 'ส่งมอบสำเร็จ',
                                            'cancelled' => 'ยกเลิก',
                                            default     => $item->status,
                                        } }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-400 mb-1">
                                    งาน: <span class="text-orange-400">{{ $item->sessionable->title ?? 'ไม่มีข้อมูลงาน' }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-2 flex flex-wrap gap-3">
                                    <span>💰 ราคา: ฿{{ number_format($item->price_agreed) }}</span>
                                    <span>🕐 เริ่ม: {{ $item->started_at?->format('d/m/Y H:i') }}</span>
                                    @if($item->total_duration_minutes > 0)
                                        <span>⏱ ใช้เวลา: {{ floor($item->total_duration_minutes / 60) }}ชม. {{ $item->total_duration_minutes % 60 }}น.</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 w-full md:w-auto mt-4 md:mt-0">
                                <a href="{{ route('frontend.sessions.show', $item->id) }}" class="flex-1 md:flex-none px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition text-center focus:ring-2 focus:ring-gray-600">
                                    เข้าห้องทำงาน (Session) →
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
