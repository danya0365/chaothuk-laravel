<div class="flex-grow flex flex-col">
    <!-- Hero Section -->
    <section class="relative bg-white dark:bg-gray-950 overflow-hidden transition-colors duration-300">
        <!-- Background Decorations -->
        <div class="absolute inset-y-0 w-full h-full pointer-events-none" aria-hidden="true">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-orange-500/10 dark:bg-orange-500/5 rounded-full blur-3xl opacity-70"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-amber-400/10 dark:bg-amber-400/5 rounded-full blur-3xl opacity-70"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-16 md:pt-32 md:pb-32 lg:flex lg:items-center lg:gap-12 text-center lg:text-left">
            <div class="lg:w-1/2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 text-xs md:text-sm font-semibold mb-4 md:mb-6 shadow-sm border border-orange-200 dark:border-orange-800/50">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    แพลตฟอร์มขนส่งเพื่อคนไทย
                </div>
                
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-gray-900 dark:text-white mb-4 md:mb-6 leading-tight">
                    ค้นหางานและรับสมัคร<br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 dark:from-orange-400 dark:to-amber-300">รวดเร็ว ปลอดภัย</span>
                </h1>
                
                <p class="text-base sm:text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium px-2 sm:px-0">
                    แหล่งรวมงานขนส่งที่ใหญ่และน่าเชื่อถือที่สุด พร้อมระบบรีวิวและการันตีคุณภาพ ให้คุณได้งานตรงใจและคนขับที่ไว้ใจได้
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 md:gap-4 px-4 sm:px-0">
                    <a href="{{ route('frontend.home') }}" class="w-full sm:w-auto px-6 py-3 md:px-8 md:py-3.5 text-sm md:text-base font-bold text-white bg-orange-600 hover:bg-orange-500 rounded-full shadow-lg shadow-orange-600/30 dark:shadow-orange-500/20 transition-all hover:-translate-y-1">
                        เข้าสู่แดชบอร์ด
                    </a>
                    <a href="{{ route('frontend.works') }}" class="w-full sm:w-auto px-6 py-3 md:px-8 md:py-3.5 text-sm md:text-base font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-full shadow-sm transition-all hover:-translate-y-1">
                        ดูงานทั้งหมด
                    </a>
                </div>
                
                <div class="mt-8 md:mt-10 flex items-center justify-center lg:justify-start gap-4 md:gap-6 text-xs md:text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        ใช้งานฟรี
                    </div>
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        มีระบบยืนยันตัวตน
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2 mt-12 lg:mt-0 relative group perspective">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-amber-300 dark:from-orange-600 dark:to-amber-500 rounded-2xl md:rounded-3xl blur-xl md:blur-2xl opacity-20 dark:opacity-30 group-hover:opacity-40 transition-opacity duration-500"></div>
                
                <div class="relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl md:rounded-3xl shadow-2xl overflow-hidden transform transition-transform duration-500 hover:scale-[1.02]">
                    <!-- Mock Browser Top -->
                    <div class="bg-gray-100 dark:bg-gray-800 px-3 py-2.5 md:px-4 md:py-3 flex items-center gap-1.5 md:gap-2 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-red-400"></div>
                        <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-green-400"></div>
                        <div class="ml-3 flex-1 bg-white/50 dark:bg-gray-900/50 rounded-md h-4 md:h-5 px-3 flex items-center shadow-inner">
                            <span class="text-[9px] md:text-[10px] text-gray-500 dark:text-gray-400 font-mono">chaothuk.com</span>
                        </div>
                    </div>
                    <!-- App Screenshot Simulation -->
                    <div class="p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 mb-1">
                                    📦
                                </div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
                                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-full"></div>
                                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-4/5"></div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-1">
                                    👷
                                </div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-full"></div>
                                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-3/4"></div>
                            </div>
                            <div class="col-span-2 bg-gradient-to-r from-orange-500 to-amber-500 p-5 rounded-xl text-white shadow-lg mt-2">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="h-5 bg-white/20 rounded w-1/3"></div>
                                    <div class="h-5 w-5 bg-white/20 rounded-full"></div>
                                </div>
                                <div class="flex gap-2 mb-2">
                                    <div class="h-2 bg-white/30 rounded w-1/4"></div>
                                    <div class="h-2 bg-white/30 rounded w-1/4"></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="h-8 bg-white/40 rounded w-1/3"></div>
                                    <div class="h-6 bg-white/20 rounded w-1/4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating badge -->
                <div class="absolute -bottom-4 -left-2 md:-bottom-6 md:-left-6 bg-white dark:bg-gray-800 rounded-xl md:rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-2.5 md:p-4 animate-bounce hover:animate-none transition-all cursor-default z-10 flex items-center gap-2 md:gap-4 scale-90 md:scale-100 origin-bottom-left" style="animation-duration: 3s;">
                    <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-lg md:text-2xl">
                        ⭐
                    </div>
                    <div>
                        <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400 font-medium">คะแนนความพึงพอใจ</div>
                        <div class="text-base md:text-xl font-black text-gray-900 dark:text-white">4.9/5.0</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 md:py-24 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 md:mb-16">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight sm:text-4xl mb-4">
                    ฟีเจอร์ที่ครบครันสำหรับทุกคน
                </h2>
                <p class="text-base md:text-lg text-gray-600 dark:text-gray-400">
                    ไม่ว่าคุณจะเป็นผู้ส่งสินค้า หรือคนขับรถ เรามีเครื่องมือที่ช่วยให้งานของคุณง่ายขึ้น
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-4 md:mb-6 shadow-inner">
                        🎯
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-3">ค้นหางานแม่นยำ</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
                        ระบบค้นหางานตามจังหวัดและประเภทรถที่อัจฉริยะ ช่วยให้คุณจับคู่งานขนส่งที่ตรงใจได้ง่ายกว่าที่เคย
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-4 md:mb-6 shadow-inner">
                        🛡️
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-3">ระบบรีวิวที่เชื่อถือได้</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
                        ตรวจสอบประวัติและคะแนนรีวิวก่อนตัดสินใจ สร้างความมั่นใจให้ทั้งผู้จ้างและผู้รับจ้าง
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-4 md:mb-6 shadow-inner">
                        💬
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-3">แชทติดต่อโดยตรง</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
                        พูดคุยและตกลงรายละเอียดงานผ่านระบบแชทในแอปพลิเคชันได้ทันที รวดเร็วและไม่ต้องผ่านคนกลาง
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Works Section -->
    @if(count($latestWorks) > 0)
    <section class="py-16 md:py-20 bg-white dark:bg-gray-950 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 md:mb-12">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight sm:text-4xl mb-2 md:mb-3">
                        งานขนส่งล่าสุด
                    </h2>
                    <p class="text-base md:text-lg text-gray-600 dark:text-gray-400">
                        เลือกดูงานใหม่ๆ ที่เปิดรับสมัครจากทั่วประเทศ
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('frontend.works') }}" class="text-orange-600 dark:text-orange-400 text-sm md:text-base font-semibold hover:underline flex items-center gap-1">
                        ดูงานทั้งหมด
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestWorks as $work)
                    <a href="{{ route('frontend.works.show', $work['id']) }}" class="group bg-gray-50 dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-800 hover:-translate-y-1">
                        <div class="aspect-video bg-gray-200 dark:bg-gray-800 overflow-hidden relative">
                            <img src="{{ $work['primary_image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/400/300' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $work['title'] }}">
                            @if($work['type'])
                                <div class="absolute top-3 left-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur rounded-full px-3 py-1 text-xs font-semibold text-gray-800 dark:text-gray-200 shadow-sm">
                                    {{ $work['type'] }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span class="flex items-center gap-1">📍 {{ $work['province'] ?? '-' }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2 line-clamp-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                {{ $work['title'] }}
                            </h3>
                            <div class="text-orange-600 dark:text-orange-400 font-black text-xl">
                                ฿{{ number_format($work['price'] ?? 0) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Stats Section -->
    <section class="py-12 md:py-20 bg-white dark:bg-gray-950 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 dark:from-gray-900 dark:to-black rounded-2xl md:rounded-3xl p-8 md:p-16 shadow-2xl overflow-hidden relative">
                <!-- Abstract BG Elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-32 h-32 md:w-64 md:h-64 rounded-full bg-orange-500/20 blur-2xl md:blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-32 h-32 md:w-64 md:h-64 rounded-full bg-blue-500/20 blur-2xl md:blur-3xl"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-0 md:gap-12 relative z-10 text-center divide-y md:divide-y-0 md:divide-x divide-gray-700/50">
                    <div class="py-4 md:py-0 px-4">
                        <div class="text-3xl md:text-5xl font-black text-white mb-1 md:mb-2">{{ number_format($stats['users_count'] ?? 0) }}</div>
                        <div class="text-orange-400 text-sm md:text-base font-medium">ผู้ใช้งานทั้งหมด</div>
                    </div>
                    <div class="py-4 md:py-0 px-4">
                        <div class="text-3xl md:text-5xl font-black text-white mb-1 md:mb-2">{{ number_format($stats['works_count'] ?? 0) }}</div>
                        <div class="text-orange-400 text-sm md:text-base font-medium">งานขนส่งในระบบ</div>
                    </div>
                    <div class="py-4 md:py-0 px-4">
                        <div class="text-3xl md:text-5xl font-black text-white mb-1 md:mb-2">{{ number_format($stats['provinces_count'] ?? 0) }}</div>
                        <div class="text-orange-400 text-sm md:text-base font-medium">จังหวัดที่รองรับ</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 bg-orange-600 dark:bg-orange-700 text-center transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl md:text-5xl font-black text-white mb-4 md:mb-6 leading-tight">
                พร้อมเริ่มงานขนส่งหรือยัง?
            </h2>
            <p class="text-base md:text-xl text-orange-100 mb-8 md:mb-10 max-w-2xl mx-auto">
                ไม่รับผ่านคนกลาง สมัครง่าย ใช้งานฟรี ดาวน์โหลดแอปพลิเคชันวันนี้
            </p>
            <a href="{{ route('frontend.auth.register') }}" class="inline-block bg-white text-orange-600 font-bold text-sm md:text-lg px-6 py-3.5 md:px-10 md:py-4 rounded-full shadow-xl hover:bg-gray-50 hover:scale-105 transition-transform duration-300">
                สมัครสมาชิก เริ่มใช้งานฟรี
            </a>
            <p class="mt-6 text-xs md:text-sm text-orange-200">
                มีบัญชีอยู่แล้ว? <a href="{{ route('frontend.auth.login') }}" class="text-white font-bold underline hover:no-underline">เข้าสู่ระบบ</a>
            </p>
        </div>
    </section>
</div>
