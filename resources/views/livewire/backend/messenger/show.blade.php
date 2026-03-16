<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('backend.messenger.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-900 dark:hover:text-white transition rounded-full hover:bg-gray-100 dark:hover:bg-gray-800" wire:navigate>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">แชท: {{ $channel->title }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    ID: {{ $channel->id }} • {{ $channel->is_direct ? 'แชทส่วนตัว (1-on-1)' : 'แชทกลุ่ม (' . $channel->participants_count . ' คน)' }}
                </p>
            </div>
        </div>
        
        <div class="flex items-center -space-x-2 overflow-hidden">
            @foreach($channel->participants as $participant)
                <div class="relative z-0 group" title="{{ $participant->author?->name }}">
                    <x-backend.avatar :src="$participant->author?->profile_image" :name="$participant->author?->name ?? '?'" size="lg" border ring="ring-white dark:ring-gray-800"/>
                    @if($participant->is_customer)
                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-blue-500 ring-2 ring-white dark:ring-gray-800" title="Customer"></span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden flex flex-col" style="min-height: 500px; max-height: 700px;">
        
        <!-- Chat History -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">
            @if($conversations->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p>ยังไม่มีข้อความในห้องแชทนี้</p>
                </div>
            @else
                
                @if($conversations->hasPages())
                    <div class="flex justify-center mb-6">
                        {{ $conversations->links() }}
                    </div>
                @endif
                
                @php $currentDate = null; @endphp
                
                @foreach($conversations as $conversation)
                    
                    @php
                        $msgDate = $conversation->created_at->format('Y-m-d');
                        $showDateHeader = $msgDate !== $currentDate;
                        $currentDate = $msgDate;
                    @endphp
                    
                    @if($showDateHeader)
                        <div class="flex justify-center my-4">
                            <span class="px-3 py-1 text-xs font-medium text-gray-500 bg-gray-200 dark:bg-gray-800 dark:text-gray-400 rounded-full">
                                {{ $conversation->created_at->translatedFormat('j F Y') }}
                            </span>
                        </div>
                    @endif
                
                    <div class="flex items-start space-x-3 group">
                        <a href="{{ $conversation->author ? route('backend.users.show', $conversation->author->id) : '#' }}" class="flex-shrink-0" target="_blank" title="ดูโปรไฟล์ผู้ใช้">
                            <x-backend.avatar :src="$conversation->author?->profile_image" :name="$conversation->author?->name ?? 'System'" size="md" />
                        </a>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-baseline space-x-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $conversation->author?->name ?? 'ระบบ' }}
                                    @if($conversation->author && ($conversation->author->roles->contains('id', \App\Enums\Role::SUPERVISOR->value) || $conversation->author->roles->contains('id', \App\Enums\Role::BACKEND->value)))
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">Admin</span>
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $conversation->created_at->format('H:i') }}</span>
                            </div>
                            
                            <div class="mt-1 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg rounded-tl-none p-3 inline-block shadow-sm">
                                @if($conversation->type === 'image')
                                    <a href="{{ Storage::url($conversation->content) }}" target="_blank" class="block">
                                        <img src="{{ Storage::url($conversation->content) }}" alt="Image" class="max-w-xs rounded-md shadow-sm border border-gray-200 dark:border-gray-700 hover:opacity-90 transition">
                                    </a>
                                @elseif($conversation->type === 'location')
                                    <div class="flex items-center space-x-2 text-blue-600 dark:text-blue-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <a href="https://maps.google.com/?q={{ $conversation->content }}" target="_blank" class="hover:underline">แชร์ตำแหน่งสถานที่ (คลิกเพื่อเปิดแผนที่)</a>
                                    </div>
                                @else
                                    {{ $conversation->content }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($conversations->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $conversations->links() }}
                    </div>
                @endif
                
            @endif
        </div>
        
        <!-- Read Only Banner -->
        <div class="bg-gray-200 dark:bg-gray-800 py-3 px-4 border-t border-gray-300 dark:border-gray-700 text-center">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                โหมดอ่านประวัติย้อนหลังเท่านั้น (Read Only)
            </p>
        </div>
    </div>
</div>
