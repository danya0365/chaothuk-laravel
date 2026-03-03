{{-- Recursive review item partial --}}
{{-- Usage: @include('livewire.frontend._review-item', ['post' => $post, 'depth' => 0]) --}}
@php $depth = $depth ?? 0; @endphp

<div class="{{ $depth > 0 ? 'ml-8 border-l-2 border-gray-800 pl-4' : 'border-t border-gray-800' }} py-3">
    <div class="flex items-start gap-3">
        <img src="{{ $post->author?->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($post->author?->name ?? 'U') }}"
             class="w-8 h-8 rounded-full object-cover flex-shrink-0 {{ $depth > 0 ? 'w-7 h-7' : '' }}" alt="">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
                <span class="font-semibold text-sm text-white">{{ $post->author?->name ?? '-' }}</span>
                @if(($post->rating ?? 0) > 0 && $depth === 0)
                    <span class="text-yellow-400 text-xs">{{ str_repeat('★', $post->rating) }}{{ str_repeat('☆', 5 - $post->rating) }}</span>
                @endif
                <span class="text-gray-600 text-[10px]">{{ $post->created_at?->diffForHumans() }}</span>
            </div>

            @if($post->title && $depth === 0)
                <p class="font-semibold text-gray-300 text-sm">{{ $post->title }}</p>
            @endif

            <p class="text-gray-300 text-sm {{ $depth > 0 ? 'text-xs text-gray-400' : '' }}">{{ $post->content }}</p>

            {{-- Reply button --}}
            @auth
            <button wire:click="startReply({{ $post->id }})"
                    class="text-[11px] text-gray-500 hover:text-orange-400 mt-1 transition">
                💬 ตอบกลับ
            </button>
            @endauth

            {{-- Reply form (inline) --}}
            @if($replyingTo === $post->id)
            <div class="mt-2 bg-gray-800 rounded-lg p-3 space-y-2">
                <textarea wire:model="replyContent" rows="2" placeholder="เขียนตอบกลับ..."
                          class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white text-xs focus:outline-none focus:border-orange-500 resize-none"></textarea>
                @error('replyContent')<p class="text-red-400 text-[10px]">{{ $message }}</p>@enderror
                <div class="flex gap-2">
                    <button wire:click="submitReply"
                            class="px-4 py-1.5 bg-orange-500 hover:bg-orange-400 text-white text-xs font-bold rounded-lg transition">
                        ส่ง
                    </button>
                    <button wire:click="startReply({{ $post->id }})"
                            class="px-4 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 text-xs rounded-lg transition">
                        ยกเลิก
                    </button>
                </div>
            </div>
            @endif

            {{-- Nested replies (recursive) --}}
            @if($post->relationLoaded('replies') && $post->replies->count() > 0)
                @foreach($post->replies as $reply)
                    @include('livewire.frontend._review-item', ['post' => $reply, 'depth' => $depth + 1])
                @endforeach
            @endif
        </div>
    </div>
</div>
