<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('backend.works.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    รายละเอียดงานเช่า
                    @if($work->is_suspended)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                            ถูกระงับการแสดงผล
                        </span>
                    @endif
                    @if($work->display_priority > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                            รายการแนะนำ
                        </span>
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">รหัส: {{ $work->code }} • สร้างเมื่อ: {{ $work->created_at->translatedFormat('d M Y H:i') }}</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <button wire:click="toggleFeature" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $work->display_priority > 0 ? 'bg-gray-600 hover:bg-gray-700' : 'bg-amber-600 hover:bg-amber-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-amber-500 transition-colors">
                <svg class="mr-2 -ml-1 h-5 w-5" fill="{{ $work->display_priority > 0 ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                {{ $work->display_priority > 0 ? 'เลิกแนะนำ' : 'ให้เป็นรายการแนะนำ' }}
            </button>
            <button wire:click="toggleSuspend" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $work->is_suspended ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 transition-colors">
                @if($work->is_suspended)
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    ยกเลิกการระงับ
                @else
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    ระงับการแสดงผล
                @endif
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Main Content (Left, 2 columns wide on XL) -->
        <div class="xl:col-span-2 space-y-6">
            
            <!-- Hero Image + Gallery Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @php
                    $galleryImages = is_array($work->images) ? $work->images : (is_string($work->images) ? json_decode($work->images, true) ?? [] : []);
                @endphp
                <div x-data="{ activeImage: '{{ $work->primary_image ? (str_contains($work->primary_image, 'http') ? $work->primary_image : Storage::url($work->primary_image)) : 'https://placehold.co/800x450?text=No+Image' }}', allImages: @js(array_merge([$work->primary_image ? (str_contains($work->primary_image, 'http') ? $work->primary_image : Storage::url($work->primary_image)) : 'https://placehold.co/800x450?text=No+Image'], array_map(fn($i) => str_contains($i, 'http') ? $i : Storage::url($i), $galleryImages))) }"
                     class="p-4 sm:p-6 space-y-4 bg-gray-50 dark:bg-gray-900/50">
                    
                    <div class="rounded-xl overflow-hidden aspect-video bg-gray-200 dark:bg-gray-900 relative">
                        <img :src="activeImage" class="w-full h-full object-contain" alt="{{ $work->title }}">
                        
                        {{-- Status badge (Frontend Style) --}}
                        @php
                            $statusBadge = match($work->work_status) {
                                'stand-by' => ['🟢', 'พร้อมรับงาน', 'bg-green-500/90'],
                                'busy'     => ['🟡', 'ไม่ว่าง', 'bg-yellow-500/90'],
                                'close'    => ['🔴', 'ปิดรับงาน', 'bg-red-500/90'],
                                default    => ['⚪', $work->work_status, 'bg-gray-500/90'],
                            };
                        @endphp
                        <span class="absolute top-3 left-3 {{ $statusBadge[2] }} text-white text-xs font-bold px-3 py-1.5 rounded-lg backdrop-blur">
                            {{ $statusBadge[0] }} {{ $statusBadge[1] }}
                        </span>
                    </div>

                    {{-- Thumbnail gallery --}}
                    @if(count($galleryImages) > 0)
                    <div class="flex gap-2 overflow-x-auto pb-1 mt-2">
                        <button @click="activeImage = '{{ $work->primary_image ? (str_contains($work->primary_image, 'http') ? $work->primary_image : Storage::url($work->primary_image)) : 'https://placehold.co/800x450?text=No+Image' }}'"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden ring-2 transition focus:outline-none"
                                :class="activeImage === '{{ $work->primary_image ? (str_contains($work->primary_image, 'http') ? $work->primary_image : Storage::url($work->primary_image)) : 'https://placehold.co/800x450?text=No+Image' }}' ? 'ring-indigo-500' : 'ring-transparent hover:ring-gray-300 dark:hover:ring-gray-600'">
                            <img src="{{ $work->primary_image ? (str_contains($work->primary_image, 'http') ? $work->primary_image : Storage::url($work->primary_image)) : 'https://placehold.co/800x450?text=No+Image' }}" class="w-full h-full object-cover" alt="">
                        </button>
                        @foreach($galleryImages as $img)
                            @php $imgUrl = str_contains($img, 'http') ? $img : Storage::url($img); @endphp
                            <button @click="activeImage = '{{ $imgUrl }}'"
                                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden ring-2 transition focus:outline-none"
                                    :class="activeImage === '{{ $imgUrl }}' ? 'ring-indigo-500' : 'ring-transparent hover:ring-gray-300 dark:hover:ring-gray-600'">
                                <img src="{{ $imgUrl }}" class="w-full h-full object-cover" alt="">
                            </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Title & Price Box (Frontend Style) --}}
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-2 flex-wrap mb-3">
                        <span class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 px-2.5 py-0.5 rounded-md text-xs font-semibold">
                            {{ $work->workType?->title ?? 'ไม่มีประเภท' }}
                        </span>
                        @foreach($work->categories as $cat)
                            <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-2.5 py-0.5 rounded-full text-[11px]">{{ $cat->name }}</span>
                        @endforeach
                        <span class="text-gray-500 dark:text-gray-400 text-[11px] flex items-center gap-1">
                           <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                           {{ $work->province?->name_th ?? 'ไม่ระบุพื้นที่' }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">{{ $work->title }}</h2>
                            <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">฿{{ number_format($work->price) }}</p>
                        </div>
                        <div class="flex items-center gap-4 flex-shrink-0 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700 text-sm">
                            <div class="text-center">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">ถูกใจ</span>
                                <span class="font-bold text-pink-500 flex items-center justify-center gap-1">❤️ {{ $work->like_count }}</span>
                            </div>
                            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
                            <div class="text-center">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">เรตติ้ง</span>
                                <span class="font-bold text-yellow-500 flex items-center justify-center gap-1">⭐ {{ number_format($work->avg_review_rating, 1) }}</span>
                            </div>
                            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
                            <div class="text-center">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">จองแล้ว</span>
                                <span class="font-bold text-indigo-500 dark:text-indigo-400 flex items-center justify-center gap-1">📋 {{ count($bookedDates) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        รายละเอียด
                    </h3>
                    <div class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-900/40 p-5 rounded-xl border border-gray-100 dark:border-gray-800">
                        {!! nl2br(e($work->description)) !!}
                    </div>
                </div>
                
                {{-- JSON Details --}}
                @if(is_array($work->details) && count($work->details) > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        ข้อมูลเพิ่มเติม (Attributes)
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($work->details as $key => $value)
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 border border-gray-100 dark:border-gray-800">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs mb-1">{{ $key }}</span>
                                <p class="text-gray-900 dark:text-white text-sm font-medium">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Availabilities Schedule -->
            @php
                $dayNames = [1 => 'จันทร์', 2 => 'อังคาร', 3 => 'พุธ', 4 => 'พฤหัสบดี', 5 => 'ศุกร์', 6 => 'เสาร์', 7 => 'อาทิตย์'];
                $dayShort = [1 => 'จ', 2 => 'อ', 3 => 'พ', 4 => 'พฤ', 5 => 'ศ', 6 => 'ส', 7 => 'อา'];
            @endphp
            @if(!empty($availabilities))
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    ตารางเวลาให้บริการมาตรฐาน
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2">
                    @for($d = 1; $d <= 7; $d++)
                        <div class="text-center rounded-xl p-3 border {{ isset($availabilities[$d]) ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-800' }}">
                            <span class="block text-sm font-bold {{ isset($availabilities[$d]) ? 'text-green-700 dark:text-green-400' : 'text-gray-400 dark:text-gray-600' }}">
                                {{ $dayShort[$d] }}
                            </span>
                            @if(isset($availabilities[$d]))
                                <span class="block text-xs text-green-600 dark:text-green-500 mt-1 leading-tight">{{ $availabilities[$d] }}</span>
                            @else
                                <span class="block text-xs text-gray-400 dark:text-gray-600 mt-1">ปิด</span>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar Content (Right, 1 column wide on XL) -->
        <div class="space-y-6">
            
            <!-- Author Reputation Card (Frontend Style Ported to Backend) -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="h-24 w-24 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                </div>
                
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 relative z-10">โพรไฟล์ผู้ลงประกาศ</h3>
                
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="flex-shrink-0 h-16 w-16">
                        <x-backend.avatar :user="$work->author" size="16" />
                    </div>
                    <div>
                        <a href="{{ route('backend.users.show', $work->author_id) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            {{ $work->author->name }}
                        </a>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $work->author->email }}</p>
                        
                        @if(!empty($providerStats) && $providerStats['trust_level'] != 'none')
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border
                                    {{ match($providerStats['trust_level']) {
                                        'diamond'  => 'bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800',
                                        'platinum' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                        'gold'     => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        'silver'   => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600',
                                        'bronze'   => 'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800',
                                        default    => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
                                    } }}">
                                    {{ $providerStats['trust_label'] }}
                                </span>
                            </div>
                        @else
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                                    ผู้ใช้ทั่วไป
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if(!empty($providerStats))
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-5 grid grid-cols-2 gap-4 relative z-10">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 text-center border border-gray-100 dark:border-gray-800">
                        <span class="block text-xl font-bold text-gray-900 dark:text-white">{{ $providerStats['total_works'] }}</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">งานทั้งหมด</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 text-center border border-gray-100 dark:border-gray-800">
                        <span class="block text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $providerStats['completed_jobs'] }}</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">งานที่สำเร็จ</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 text-center border border-gray-100 dark:border-gray-800 col-span-2 flex justify-between items-center px-5">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">อัตราความสำเร็จ</span>
                        <span class="text-lg font-bold {{ $providerStats['completion_rate'] >= 80 ? 'text-emerald-500' : 'text-amber-500' }}">
                            {{ $providerStats['completion_rate'] }}%
                        </span>
                    </div>
                </div>
                @endif
                
                <div class="mt-4 flex flex-col gap-2 relative z-10 w-full text-center py-2.5">
                    <a href="{{ route('backend.users.show', $work->author_id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition w-full block border border-indigo-200 dark:border-indigo-800 rounded-lg py-2 bg-indigo-50 dark:bg-indigo-900/20">
                        ดูข้อมูลรหัสสมาชิก
                    </a>
                </div>
            </div>

            <!-- Mini Map (Backend Version) -->
            @if($work->latitude && $work->longitude)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">พิกัดทางภูมิศาสตร์</h3>
                
                <div class="bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 aspect-video flex flex-col items-center justify-center text-center p-4">
                    <svg class="h-10 w-10 text-rose-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <p class="font-mono text-sm text-gray-800 dark:text-gray-200 font-bold tracking-tight">
                        {{ number_format($work->latitude, 6) }}<br>
                        {{ number_format($work->longitude, 6) }}
                    </p>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $work->latitude }},{{ $work->longitude }}" target="_blank" class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        เปิดใน Google Maps
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
