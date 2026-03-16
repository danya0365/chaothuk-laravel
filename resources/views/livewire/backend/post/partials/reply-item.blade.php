@props(['reply', 'level' => 0])

<div class="relative flex mt-4 pl-4 {{ $level > 0 ? 'border-l-2 border-gray-100 dark:border-gray-700 ml-4' : '' }}">
    
    <!-- User Avatar avatar -->
    <div class="flex-shrink-0 mr-3 mt-1">
        <x-backend.avatar :user="$reply->author" size="sm" />
    </div>

    <!-- Reply Content Block -->
    <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border {{ $reply->is_suspended ? 'border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-900/20' : 'border-gray-200 dark:border-gray-700' }}">
        
        <!-- Header: Author & Time & Badges -->
        <div class="flex flex-wrap items-center justify-between mb-2 gap-2">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-sm text-gray-900 dark:text-white">
                    {{ optional($reply->author)->name ?? 'User (Deleted)' }}
                </span>

                @if(optional($reply->author)->isCanAccessBackend())
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-400">
                        ทีมงาน (Admin)
                    </span>
                @endif
                
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    &bull; {{ $reply->created_at->diffForHumans() }}
                </span>
            </div>
            
            <!-- Badges -->
            <div class="flex items-center space-x-2">
                @if($reply->is_suspended)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                        ถูกระงับ
                    </span>
                @endif
            </div>
        </div>

        <!-- Body -->
        <div class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line mb-3">
            {{ $reply->content }}
        </div>

        <!-- Action Bar -->
        <div class="flex items-center space-x-4 pt-2 border-t border-gray-100 dark:border-gray-700">
            <!-- Reply to this specifically -->
            <button wire:click="setReplyTo({{ $reply->id }})" class="text-xs font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 flex items-center transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                ตอบกลับ
            </button>
            
            <!-- Admin Actions -->
            <div class="flex-1 flex justify-end items-center space-x-3">
                <!-- Suspend -->
                <button wire:click="toggleSuspend({{ $reply->id }})" class="text-xs font-medium {{ $reply->is_suspended ? 'text-green-600 hover:text-green-800' : 'text-orange-500 hover:text-orange-700' }} transition-colors">
                    {{ $reply->is_suspended ? 'ยกเลิกการระงับ' : 'ระงับข้อความ' }}
                </button>
                
                <span class="text-gray-300 dark:text-gray-600">|</span>
                
                <!-- Delete -->
                <button wire:click="confirmDelete({{ $reply->id }})" class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors">
                    ลบทิ้ง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Recursive Render Children (Level Limit safely to 3 for UI purposes) -->
@if($reply->replies && $reply->replies->count() > 0 && $level < 3)
    <div class="ml-4 md:ml-8 mt-2 space-y-2">
        @foreach($reply->replies as $childReply)
            @include('livewire.backend.post.partials.reply-item', ['reply' => $childReply, 'level' => $level + 1])
        @endforeach
    </div>
@endif
