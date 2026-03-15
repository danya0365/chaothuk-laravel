<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('backend.posts.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    รายละเอียดการโพสต์/รีวิว
                    @if($post->is_suspended)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                            ถูกระงับการแสดงผล
                        </span>
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">โพสต์เมื่อ: {{ $post->created_at->translatedFormat('d M Y H:i') }}</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <button wire:click="toggleSuspend" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $post->is_suspended ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 transition-colors">
                @if($post->is_suspended)
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

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Related Item Banner -->
        @if($post->works->count() > 0)
            <div class="bg-indigo-50 dark:bg-indigo-900/30 border-b border-indigo-100 dark:border-indigo-800 p-4">
                <p class="text-sm font-medium text-indigo-800 dark:text-indigo-300">
                    อ้างอิงถึง <strong>งานเช่า/บริการ:</strong> <a href="{{ route('backend.works.show', $post->works->first()->id) }}" class="underline hover:text-indigo-600">{{ $post->works->first()->title }}</a>
                </p>
            </div>
        @elseif($post->recruits->count() > 0)
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border-b border-emerald-100 dark:border-emerald-800 p-4">
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">
                    อ้างอิงถึง <strong>ประกาศรับสมัคร:</strong> <a href="{{ route('backend.recruits.show', $post->recruits->first()->id) }}" class="underline hover:text-emerald-600">{{ $post->recruits->first()->title }}</a>
                </p>
            </div>
        @endif
        
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <!-- Author Info -->
                <div class="flex items-center gap-3">
                    <x-backend.avatar :user="$post->author" size="12" />
                    <div>
                        <a href="{{ route('backend.users.show', $post->author_id) }}" class="text-base font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            {{ $post->author->name }}
                        </a>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->author->email }}</p>
                    </div>
                </div>

                <!-- Rating -->
                @if($post->rating)
                    <div class="bg-amber-50 dark:bg-amber-900/20 px-4 py-2 rounded-lg border border-amber-100 dark:border-amber-800 flex flex-col items-center">
                        <span class="text-xs font-semibold text-amber-800 dark:text-amber-500 mb-1">ให้คะแนน</span>
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $post->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div>
                @if($post->title)
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $post->title }}</h2>
                @endif
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>

            <!-- Images (Post may have attached images) -->
            @php
                $images = is_array($post->images) ? $post->images : (is_string($post->images) ? json_decode($post->images, true) ?? [] : []);
            @endphp
            @if(count($images) > 0)
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">รูปภาพแนบ</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($images as $img)
                            <a href="{{ str_contains($img, 'http') ? $img : Storage::url($img) }}" target="_blank" class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 aspect-square group block">
                                <img src="{{ str_contains($img, 'http') ? $img : Storage::url($img) }}" class="w-full h-full object-cover group-hover:opacity-75 transition-opacity" alt="">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Replies -->
    @if($replies && count($replies) > 0)
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                คอมเมนต์ตอบกลับ ({{ count($replies) }})
            </h3>
            
            @foreach($replies as $reply)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-5 ml-4 sm:ml-8 relative">
                    <div class="absolute -left-4 sm:-left-8 top-10 border-l-2 border-b-2 border-gray-200 dark:border-gray-700 w-4 sm:w-8 h-4 rounded-bl-xl"></div>
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <x-backend.avatar :user="$reply->author" size="8" />
                            <div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $reply->author->name ?? 'Unknown' }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {!! nl2br(e($reply->content)) !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
