<div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
        <!-- Decoration background -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 text-center mb-10">
            <span class="text-6xl mb-4 block">🚛</span>
            <h1 class="text-4xl md:text-5xl font-black bg-clip-text text-transparent bg-gradient-to-r from-orange-500 to-amber-300 tracking-tight mb-4">
                {{ config('app.name', 'Chaothuk') }}
            </h1>
            <p class="text-xl text-gray-400 font-medium">แพลตฟอร์มขนส่งและรับสมัครงานเพื่อคนไทย</p>
        </div>

        <div class="relative z-10 prose prose-invert mx-auto">
            <p class="text-gray-300 text-center leading-relaxed mb-8">
                Chaothuk คือแพลตฟอร์มที่เชื่อมโยงระหว่างผู้ให้บริการรถรับจ้าง รถขนส่ง กับผู้ที่ต้องการหารถขนย้ายของ ไม่ว่าจะเป็นการย้ายบ้าน ย้ายหอพัก หรือขนส่งสินค้า เรามุ่งมั่นที่จะสร้างชุมชนที่โปร่งใส ปลอดภัย และใช้งานง่าย ทั้งยังมีระบบจัดการรับสมัครงานภายในระบบเดียว
            </p>

            <div class="bg-gray-950/50 rounded-2xl p-6 border border-gray-800/60 max-w-sm mx-auto flex flex-col items-center justify-center space-y-4">
                <div class="text-gray-400 text-sm uppercase tracking-widest font-bold">ข้อมูลระบบ</div>
                
                <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm w-full">
                    <div class="text-gray-500 text-right">เวอร์ชัน</div>
                    <div class="text-white font-mono">{{ config('app.version', '1.0.0') }}</div>

                    <div class="text-gray-500 text-right">บิลด์ (Build)</div>
                    <div class="text-white font-mono">{{ config('app.build', 'local') }}</div>

                    @if(config('app.env') !== 'production')
                        <div class="text-gray-500 text-right items-center flex justify-end">สภาพแวดล้อม</div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-900/30 text-yellow-400 border border-yellow-800/50">
                                {{ strtoupper(config('app.env')) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-12 text-center relative z-10">
            <a href="{{ route('frontend.home') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-gray-900 bg-white rounded-full hover:bg-gray-100 transition-colors focus:ring-4 focus:ring-white/20">
                กลับสู่หน้าแรก
            </a>
        </div>
    </div>
</div>
