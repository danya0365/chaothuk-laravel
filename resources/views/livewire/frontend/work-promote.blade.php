<div class="max-w-3xl mx-auto px-4 py-8 relative">
    
    <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        กลับ
    </a>

    <div class="flex items-center mb-6 gap-3">
        <div class="w-12 h-12 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-xl">
            ⭐
        </div>
        <div>
            <h1 class="text-2xl font-bold text-white">โปรโมทให้งานของคุณโดดเด่น</h1>
            <p class="text-sm text-gray-400">ดันโพสต์ให้งานของคุณไปอยู่บนแบนเนอร์หน้าแรกเพื่อดึงดูดผู้ว่าจ้าง</p>
        </div>
    </div>

    @if($errorMessage)
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 flex items-start gap-3">
            <span class="text-red-400 text-lg mt-0.5">⚠️</span>
            <div>
                <h3 class="font-semibold text-red-400">ทำรายการไม่สำเร็จ</h3>
                <p class="text-sm text-red-300 mt-1">{{ $errorMessage }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Package Selection -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-white mb-3">เลือกแพ็กเกจโปรโมท</h2>
            
            @foreach($packages as $days => $pkg)
                <label class="block relative cursor-pointer group">
                    <input type="radio" wire:model.live="selectedPackage" name="package" value="{{ $days }}" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 transition-all {{ $selectedPackage == $days ? 'border-orange-500 bg-orange-500/10' : 'border-gray-700 bg-gray-900 group-hover:border-gray-500' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-white">{{ $days }} วัน</p>
                                <p class="text-xs text-gray-400 mt-1">แสดงผลบนแบนเนอร์ Featured อย่างต่อเนื่อง</p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-xl text-orange-400">฿{{ $pkg['price'] }}</p>
                            </div>
                        </div>
                        
                        <div class="absolute top-4 right-4 text-orange-500 opacity-0 peer-checked:opacity-100 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>

        <!-- Summary & Checkout -->
        <div>
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sticky top-24 shadow-2xl">
                <h2 class="text-lg font-bold text-white mb-4">สรุปการสั่งซื้อ</h2>
                
                <div class="flex items-center gap-4 p-3 bg-gray-800 rounded-lg mb-6">
                    <img src="{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/100/100' }}" class="w-16 h-16 rounded-lg object-cover" alt="">
                    <div class="min-w-0">
                        <p class="font-semibold text-white truncate text-sm">{{ $work->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">ราคาอ้างอิง: ฿{{ number_format($work->price) }}</p>
                    </div>
                </div>

                <div class="space-y-3 pb-6 border-b border-gray-800">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">แพ็กเกจที่เลือก</span>
                        <span class="text-white font-medium">{{ $packages[$selectedPackage]['days'] }} วัน</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">ยอดเงินประชำระ (เครดิต)</span>
                        <span class="text-orange-400 font-bold text-lg">฿{{ number_format($packages[$selectedPackage]['price']) }}</span>
                    </div>
                </div>

                <div class="py-4">
                    <div class="flex justify-between items-center text-sm mb-4">
                        <span class="text-gray-400">เงินในกระเป๋าของคุณ</span>
                        <span class="text-white font-mono">฿{{ number_format($walletBalance, 2) }}</span>
                    </div>

                    @if($walletBalance < $packages[$selectedPackage]['price'])
                        <div class="p-4 bg-red-500/10 rounded-xl mb-4 text-center border border-red-500/20">
                            <span class="block text-red-400 text-sm font-semibold mb-2">ยอดเครดิตไม่พอจ่าย</span>
                            <a href="{{ route('frontend.my_wallet') }}" class="inline-block px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition">
                                ไปหน้ากระเป๋าเงินเพื่อเติมเครดิต
                            </a>
                        </div>
                    @endif

                    <button wire:click="promote" 
                            wire:loading.attr="disabled"
                            @if($walletBalance < $packages[$selectedPackage]['price']) disabled @endif
                            class="w-full py-3 rounded-xl font-bold transition flex justify-center items-center gap-2 {{ $walletBalance < $packages[$selectedPackage]['price'] ? 'bg-gray-700 text-gray-500 cursor-not-allowed' : 'bg-orange-500 hover:bg-orange-400 text-white' }}">
                        <span wire:loading.remove>ยืนยันและชำระเงิน</span>
                        <span wire:loading>กำลังประมวลผล...</span>
                    </button>
                    
                    <p class="text-[10px] text-gray-500 text-center mt-3">ระบบแจ้งผลตัดเครดิตทันที ไม่สามารถขอคืนเงินได้หลังทำรายการสำเร็จ</p>
                </div>
            </div>
        </div>
    </div>
</div>
