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
                    @if($work->activeFeature)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                            รายการแนะนำ
                        </span>
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">รหัส: {{ $work->code }} • สร้างเมื่อ: {{ $work->created_at->translatedFormat('d M Y H:i') }}</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @if($work->activeFeature)
                <button wire:click="confirmRevokeFeature" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-gray-500 transition-colors">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    ยกเลิกการโปรโมท
                </button>
                <button wire:click="openFeatureModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 transition-colors">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    ขยายเวลาแนะนำ
                </button>
            @else
                <button wire:click="openFeatureModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-amber-500 transition-colors">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    ให้เป็นรายการแนะนำ
                </button>
            @endif
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

            <!-- Recent Bookings -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        รายการจองล่าสุด (10 รายการ)
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
                        <p class="text-sm text-gray-500 dark:text-gray-400">ยังไม่มีรายการจอง</p>
                    </div>
                @endif
            </div>

            <!-- Recent Reviews -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        รีวิวล่าสุด (10 รายการ)
                    </h3>
                </div>
                
                @if(count($recentReviews) > 0)
                    <div class="space-y-4">
                        @foreach($recentReviews as $review)
                            <div class="p-4 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <x-backend.avatar :user="$review->author" size="10" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $review->author->name ?? 'ผู้ใช้ทั่วไป' }}</p>
                                            <div class="flex items-center mt-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-gray-400">{{ $review->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <div class="mt-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-3">{{ $review->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">ยังไม่มีรีวิว</p>
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
    
    <!-- Feature Assignment Modal -->
    @if($showFeatureModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeFeatureModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 dark:bg-amber-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                {{ $isAppendingFeature ? 'ขยายเวลาการโปรโมทงาน' : 'ตั้งเป็นรายการแนะนำ (Feature)' }}
                            </h3>
                            <div class="mt-4 space-y-4">
                                @if($isAppendingFeature)
                                    <div class="rounded-md bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                            </div>
                                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                                    งานนี้เป็นรายการแนะนำอยู่แล้ว จำนวนวันที่ใส่เพิ่มจะถูก <b>บวกต่อท้าย</b> จากวันหมดอายุเดิม
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <label for="featureDays" class="block text-sm font-medium text-gray-700 dark:text-gray-300">จำนวนวันที่จะโปรโมท <span class="text-red-500">*</span></label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" wire:model="featureDays" id="featureDays" min="1" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="7">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">วัน</span>
                                        </div>
                                    </div>
                                    @error('featureDays') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="featureNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">หมายเหตุ (ภายใน) <span class="text-gray-400 font-normal text-xs">- ไม่บังคับ</span></label>
                                    <div class="mt-1">
                                        <textarea wire:model="featureNote" id="featureNote" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="เช่น ชดเชยระบบล่ม, แอดมินให้ฟรีพิเศษ..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveFeature" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            ยืนยันการตั้งค่า
                        </button>
                        <button wire:click="closeFeatureModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Revoke Feature Confirmation Modal -->
    @if($confirmingRevokeFeature)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeRevokeFeatureModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ยืนยันการยกเลิกโปรโมท</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <p>คุณแน่ใจหรือไม่ที่จะยกเลิกการเป็นรายการแนะนำของงานนี้? เวลาที่เหลือทั้งหมดจะถูกตัดทิ้งทันที และไม่สามารถกู้คืนได้</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="revokeFeature" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">ยืนยันการยกเลิก</button>
                        <button wire:click="closeRevokeFeatureModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">ปิดหน้าต่าง</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
