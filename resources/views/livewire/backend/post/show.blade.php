<div class="p-6 sm:p-10 max-w-5xl mx-auto space-y-6 pb-24">
    <!-- Header Navigation -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('backend.posts.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ควบคุมโพสต์/รีวิวแบบละเอียด</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Moderation Post Details & Recursive Replies</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800 transition-all">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: The Main Discussion -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- MAIN POST CARD -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border {{ $post->is_suspended ? 'border-red-400 dark:border-red-600' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden">
                <div class="p-5 sm:p-6">
                    
                    <!-- Context Badge -->
                    <div class="mb-4">
                        @if($post->works && $post->works->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                💼 รีวิวงานเช่า
                            </span>
                        @elseif($post->recruits && $post->recruits->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">
                                📢 รีวิวประกาศรับสมัคร
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                💬 โพสต์ชุมชนทั่วไป
                            </span>
                        @endif

                        @if($post->is_suspended)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-800">
                                🔴 เนื้อหาถูกระงับ
                            </span>
                        @endif
                    </div>

                    <!-- Author Header -->
                    <div class="flex items-center mb-5">
                        <x-backend.avatar :user="$post->author" size="md" />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('backend.users.show', $post->author_id) }}" class="hover:underline hover:text-indigo-500">
                                    {{ optional($post->author)->name ?? 'Unknown Author' }}
                                </a>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                โพสต์เมื่อ {{ $post->created_at->format('d M Y, H:i') }} ({{ $post->created_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>

                    <!-- Post Body -->
                    @if($post->title)
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h2>
                    @endif

                    @if($post->rating)
                        <div class="flex items-center mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $post->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    @endif

                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 whitespace-pre-line mb-6">
                        {{ $post->content }}
                    </div>

                    @if($post->images && is_array($post->images) && count($post->images) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-6">
                            @foreach($post->images as $image)
                                <a href="{{ image_url($image) }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:opacity-90 transition">
                                    <img src="{{ image_url($image) }}" alt="Post image" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Main Post Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="setReplyTo({{ $post->id }})" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                            ตอบกลับโพสต์หลัก
                        </button>
                        
                        <div class="flex items-center space-x-3">
                            <button wire:click="toggleSuspend({{ $post->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $post->is_suspended ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' }} transition-colors">
                                @if($post->is_suspended)
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    ยกเลิกการระงับ
                                @else
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    ระงับการแสดงผล
                                @endif
                            </button>
                            <button wire:click="confirmDelete({{ $post->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                ลบทิ้ง
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMMENTS THREAD -->
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">คอมเมนต์ตอบกลับ ({{ $replies->count() }})</h3>
                
                @if($replies->count() > 0)
                    <div class="space-y-4">
                        @foreach($replies as $reply)
                            @include('livewire.backend.post.partials.reply-item', ['reply' => $reply, 'level' => 0])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 border-dashed">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">ยังไม่มีคอมเมนต์</h3>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Column: Audit & Report History -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 font-semibold text-gray-800 dark:text-gray-200">
                    ประวัติการถูกรีพอร์ต (Report History)
                </div>
                <div class="p-5">
                    @if($reports->count() > 0)
                        <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/30 p-4 border border-yellow-200 dark:border-yellow-800 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                        คำเตือน: เนื้อหานี้ถูกรีพอร์ต {{ $reports->count() }} ครั้ง
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            @foreach($reports as $report)
                                <li class="py-3 flex justify-between gap-x-6">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">
                                            <a href="{{ route('backend.users.show', $report->reporter_id) }}" class="hover:underline text-indigo-600 dark:text-indigo-400">
                                                {{ optional($report->reporter)->name ?? 'User' }}
                                            </a>
                                        </p>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500 dark:text-gray-400">เหตุผล: {{ $report->reason }}</p>
                                        @if($report->description)
                                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 whitespace-nowrap overflow-hidden text-ellipsis italic">"{{ $report->description }}"</p>
                                        @endif
                                    </div>
                                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                                        <p class="text-sm leading-6 text-gray-900 dark:text-white">สถานะ</p>
                                        <p class="mt-1 text-xs leading-5 {{ $report->status === 'pending' ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                                            {{ $report->status === 'pending' ? 'รอดำเนินการ' : 'ตรวจสอบแล้ว' }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">โพสต์นี้มีประวัติใสสะอาด ไม่เคยถูกรายงาน</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Admin Reply Bar -->
    <div class="fixed bottom-0 inset-x-0 pb-4 pt-4 px-4 bg-white dark:bg-gray-800 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t border-gray-200 dark:border-gray-700 z-40 transition-transform duration-300 transform md:pl-64" 
        x-data="{ focusInput() { $refs.replyInput.focus(); } }" 
        @focus-reply-input.window="focusInput()">
        <div class="max-w-5xl mx-auto">
            <form wire:submit="submitAdminReply">
                <div class="flex items-end gap-4">
                    <div class="flex-grow">
                        @if($replyToId != $post->id)
                            <div class="flex items-center justify-between mb-2 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 py-1 px-3 rounded-md border border-indigo-100 dark:border-indigo-800">
                                <span>กำลังเตรียมตอบกลับคอมเมนต์ย่อย #{{ $replyToId }}</span>
                                <button type="button" wire:click="cancelReplyTo" class="hover:text-indigo-800 dark:hover:text-indigo-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        @endif
                        <label for="replyInput" class="sr-only">ตอบกลับในฐานะผู้ดูแลระบบ</label>
                        <textarea 
                            wire:model.defer="replyBody" 
                            id="replyInput" 
                            x-ref="replyInput"
                            rows="2" 
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm resize-none" 
                            placeholder="พิมพ์ข้อความตอบกลับในฐานะ Admin Officials (ทีมงานแอดมิน)..."></textarea>
                        @error('replyBody')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 mt-auto border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors h-[52px]">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        ส่งข้อความ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($itemToDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="cancelDelete"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ยืนยันลบเนื้อหาถาวร</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                คุณแน่ใจหรือไม่ที่จะลบเนื้อหานี้ (และข้อความตอบกลับย่อยทั้งหมด)? การกระทำนี้ไม่สามารถย้อนกลับได้
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteItem" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">ยืนยันการลบ</button>
                        <button wire:click="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
