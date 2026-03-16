<div class="max-w-5xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    <h1 class="text-xl md:text-2xl font-bold text-white mb-4 md:mb-6">💬 แชท</h1>

    @if(empty($channels))
        <div class="text-center py-10 md:py-16">
            <div class="text-4xl md:text-5xl mb-2 md:mb-3">💬</div>
            <p class="text-gray-400 font-medium text-sm md:text-base">ยังไม่มีแชท</p>
            <p class="text-gray-500 text-xs md:text-sm mt-0.5 md:mt-1">เริ่มแชทโดยจองงานหรือรับสมัครงาน</p>
        </div>
    @else
        <div class="flex flex-col md:flex-row gap-3 md:gap-4 h-[calc(100vh-140px)] md:h-[calc(100vh-180px)] min-h-[400px]">

            {{-- Channel List --}}
            <div class="w-full md:w-80 h-[35%] md:h-auto bg-gray-900 rounded-2xl overflow-hidden flex flex-col flex-shrink-0">
                <div class="p-2 md:p-3 border-b border-gray-800">
                    <p class="text-gray-400 text-[10px] md:text-xs font-medium">การสนทนา ({{ count($channels) }})</p>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @foreach($channels as $ch)
                        <button wire:click="openChannel({{ $ch['id'] }})"
                                class="w-full flex items-center gap-2 md:gap-3 px-2 py-2 md:px-3 md:py-3 text-left transition
                                       {{ $activeChannelId === $ch['id'] ? 'bg-orange-500/10 border-l-2 border-orange-500' : 'hover:bg-gray-800' }}">
                            <img src="{{ $ch['avatar'] }}"
                                 class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover flex-shrink-0" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-xs md:text-sm truncate {{ $activeChannelId === $ch['id'] ? 'text-orange-400' : 'text-white' }}">
                                    {{ $ch['title'] }}
                                </p>
                                <p class="text-[10px] md:text-xs text-gray-500 truncate">{{ $ch['last_msg'] }}</p>
                            </div>
                            @if($ch['last_time'])
                                <span class="text-[9px] md:text-[10px] text-gray-600 flex-shrink-0">{{ $ch['last_time'] }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Conversation Area --}}
            <div class="flex-1 h-[65%] md:h-auto bg-gray-900 rounded-2xl overflow-hidden flex flex-col">
                @if($activeChannel)
                    {{-- Chat header --}}
                    <div class="flex items-center gap-2 md:gap-3 px-3 py-2 md:px-4 md:py-3 border-b border-gray-800">
                        <img src="{{ $activeChannel['avatar'] }}"
                             class="w-6 h-6 md:w-8 md:h-8 rounded-full object-cover" alt="">
                        <p class="font-semibold text-white text-xs md:text-sm">{{ $activeChannel['title'] }}</p>
                    </div>

                    {{-- Messages --}}
                    <div class="flex-1 overflow-y-auto px-3 py-3 md:px-4 md:py-4 space-y-2 md:space-y-3" id="chat-messages">
                        @foreach($conversations ?? [] as $msg)
                            <div class="flex {{ $msg['is_mine'] ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end gap-1.5 md:gap-2 max-w-[85%] md:max-w-[75%]">
                                    @if(!$msg['is_mine'])
                                        <img src="{{ $msg['avatar'] }}"
                                             class="w-5 h-5 md:w-6 md:h-6 rounded-full object-cover flex-shrink-0" alt="">
                                    @endif
                                    <div class="rounded-2xl px-3 py-2 md:px-4 md:py-2.5
                                        {{ $msg['is_mine']
                                            ? 'bg-orange-500 text-white rounded-br-sm'
                                            : 'bg-gray-800 text-gray-200 rounded-bl-sm' }}">
                                        <p class="text-xs md:text-sm">{{ $msg['content'] }}</p>
                                        <p class="text-[9px] md:text-[10px] mt-0.5 md:mt-1 {{ $msg['is_mine'] ? 'text-orange-200' : 'text-gray-500' }}">
                                            {{ $msg['time'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Input --}}
                    <form wire:submit="sendMessage" class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-3 border-t border-gray-800">
                        <input wire:model="newMessage" type="text" placeholder="พิมพ์ข้อความ..."
                               class="flex-1 bg-gray-800 border border-gray-700 rounded-full px-3 py-2 md:px-4 md:py-2.5 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500 transition"
                               autocomplete="off">
                        <button type="submit"
                                class="p-2 md:p-2.5 bg-orange-500 hover:bg-orange-400 text-white rounded-full transition flex-shrink-0 flex justify-center items-center w-8 h-8 md:w-10 md:h-10 text-xs md:text-sm">
                            ➤
                        </button>
                    </form>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500 text-xs md:text-sm">
                        <p>เลือกการสนทนาเพื่อเริ่มแชท</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
