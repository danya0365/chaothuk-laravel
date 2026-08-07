<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('backend.recruits.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    รายละเอียดประกาศจ้างงาน
                    @if($recruit->is_suspended)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                            ถูกระงับการแสดงผล
                        </span>
                    @endif
                    @if($recruit->display_priority > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                            รายการแนะนำ
                        </span>
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ID: {{ $recruit->id }} • สร้างเมื่อ: {{ $recruit->created_at->translatedFormat('d M Y H:i') }}</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <button wire:click="toggleFeature" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $recruit->display_priority > 0 ? 'bg-gray-600 hover:bg-gray-700' : 'bg-amber-600 hover:bg-amber-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-amber-500 transition-colors">
                <svg class="mr-2 -ml-1 h-5 w-5" fill="{{ $recruit->display_priority > 0 ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                {{ $recruit->display_priority > 0 ? 'เลิกแนะนำ' : 'ให้เป็นรายการแนะนำ' }}
            </button>
            <button wire:click="toggleSuspend" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $recruit->is_suspended ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 transition-colors">
                @if($recruit->is_suspended)
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
                    $galleryImages = is_array($recruit->images) ? $recruit->images : (is_string($recruit->images) ? json_decode($recruit->images, true) ?? [] : []);
                    $primaryImageUrl = image_url($recruit->primary_image, 'https://placehold.co/800x450?text=No+Image');
                    $galleryImageUrls = array_map(fn ($i) => image_url($i), $galleryImages);
                @endphp
                <div x-data="{ activeImage: @js($primaryImageUrl), allImages: @js(array_merge([$primaryImageUrl], $galleryImageUrls)) }"
                     class="p-4 sm:p-6 space-y-4 bg-gray-50 dark:bg-gray-900/50">

                    <div class="rounded-xl overflow-hidden aspect-video bg-gray-200 dark:bg-gray-900 relative">
                        <img :src="activeImage" class="w-full h-full object-contain" alt="{{ $recruit->title }}">

                        {{-- Status badge (Frontend Style) --}}
                        @php
                            $statusBadge = match($recruit->recruit_status) {
                                'looking_for' => ['🟢', 'กำลังหาคน', 'bg-green-500/90'],
                                'found'     => ['🔴', 'ได้คนแล้ว', 'bg-red-500/90'],
                                default    => ['⚪', $recruit->recruit_status, 'bg-gray-500/90'],
                            };
                        @endphp
                        <span class="absolute top-3 left-3 {{ $statusBadge[2] }} text-white text-xs font-bold px-3 py-1.5 rounded-lg backdrop-blur">
                            {{ $statusBadge[0] }} {{ $statusBadge[1] }}
                        </span>
                    </div>

                    {{-- Thumbnail gallery --}}
                    @if(count($galleryImages) > 0)
                    <div class="flex gap-2 overflow-x-auto pb-1 mt-2">
                        <button @click="activeImage = @js($primaryImageUrl)"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden ring-2 transition focus:outline-none"
                                :class="activeImage === @js($primaryImageUrl) ? 'ring-indigo-500' : 'ring-transparent hover:ring-gray-300 dark:hover:ring-gray-600'">
                            <img src="{{ $primaryImageUrl }}" class="w-full h-full object-cover" alt="">
                        </button>
                        @foreach($galleryImages as $i => $img)
                            <button @click="activeImage = @js($galleryImageUrls[$i])"
                                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden ring-2 transition focus:outline-none"
                                    :class="activeImage === @js($galleryImageUrls[$i]) ? 'ring-indigo-500' : 'ring-transparent hover:ring-gray-300 dark:hover:ring-gray-600'">
                                <img src="{{ $galleryImageUrls[$i] }}" class="w-full h-full object-cover" alt="">
                            </button>
                            <button @click="activeImage = @js($galleryImageUrls[$i])"
                                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden ring-2 transition focus:outline-none"
                                    :class="activeImage === @js($galleryImageUrls[$i]) ? 'ring-indigo-500' : 'ring-transparent hover:ring-gray-300 dark:hover:ring-gray-600'">
                                <img src="{{ $galleryImageUrls[$i] }}" class="w-full h-full object-cover" alt="">
                            </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Title & Price Box (Frontend Style) --}}
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-2 flex-wrap mb-3">
                        <span class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 px-2.5 py-0.5 rounded-md text-xs font-semibold">
                            {{ $recruit->workType?->title ?? 'ไม่มีประเภท' }}
                        </span>
                        @foreach($recruit->categories as $cat)
                            <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-2.5 py-0.5 rounded-full text-[11px]">{{ $cat->name }}</span>
                        @endforeach
                        <span class="text-gray-500 dark:text-gray-400 text-[11px] flex items-center gap-1">
                           <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                           {{ $recruit->province?->name_th ?? 'ไม่ระบุพื้นที่' }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">{{ $recruit->title }}</h2>
                            <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">฿{{ number_format($recruit->budget) }}</p>
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
                        {!! nl2br(e($recruit->description)) !!}
                    </div>
                </div>
                
                {{-- JSON Details --}}
                @if(is_array($recruit->details) && count($recruit->details) > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        ข้อมูลเพิ่มเติม (Attributes)
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($recruit->details as $key => $value)
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 border border-gray-100 dark:border-gray-800">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs mb-1">{{ $key }}</span>
                                <p class="text-gray-900 dark:text-white text-sm font-medium">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Bookings / Applications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        ผู้สมัครล่าสุด (10 รายการ)
                    </h3>
                </div>
                
                @if(count($recentBookings) > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-start justify-between p-4 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <x-backend.avatar :user="$booking->author" size="10" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $booking->author->name ?? 'ผู้ใช้ทั่วไป' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><span class="font-medium text-gray-700 dark:text-gray-300">เบอร์โทร:</span> {{ $booking->mobile_phone ?? '-' }}</p>
                                        @if($booking->customer_message)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 italic">"{{ Str::limit($booking->customer_message, 100) }}"</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border
                                        {{ match($booking->booking_status) {
                                            'waiting-to-confirm' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'confirm', 'paid', 'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400',
                                            'completed', 'close' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400',
                                            'cancel', 'rejected' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300'
                                        } }}">
                                        {{ $booking->booking_status }}
                                    </span>
                                    <p class="text-[11px] text-gray-400 mt-1">{{ Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">ยังไม่มีผู้สมัครจองงานนี้</p>
                    </div>
                @endif
            </div>

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
                        <x-backend.avatar :user="$recruit->author" size="16" />
                    </div>
                    <div>
                        <a href="{{ route('backend.users.show', $recruit->author_id) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            {{ $recruit->author->name }}
                        </a>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $recruit->author->email }}</p>
                    </div>
                </div>
                
                <div class="mt-4 flex flex-col gap-2 relative z-10 w-full text-center py-2.5">
                    <a href="{{ route('backend.users.show', $recruit->author_id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition w-full block border border-indigo-200 dark:border-indigo-800 rounded-lg py-2 bg-indigo-50 dark:bg-indigo-900/20">
                        ดูข้อมูลรหัสสมาชิก
                    </a>
                </div>
            </div>

            <!-- Mini Map (Backend Version) -->
            @if($recruit->latitude && $recruit->longitude)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">พิกัดทางภูมิศาสตร์</h3>
                
                <div class="bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 aspect-video flex flex-col items-center justify-center text-center p-4">
                    <svg class="h-10 w-10 text-rose-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <p class="font-mono text-sm text-gray-800 dark:text-gray-200 font-bold tracking-tight">
                        {{ number_format($recruit->latitude, 6) }}<br>
                        {{ number_format($recruit->longitude, 6) }}
                    </p>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $recruit->latitude }},{{ $recruit->longitude }}" target="_blank" class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        เปิดใน Google Maps
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
