<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">แชททั้งหมด (Messenger Channels)</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                รายการประวัติการสนทนาทั้งหมดในระบบ (อ่านได้อย่างเดียว)
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <!-- Toolbar -->
        <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700 sm:flex sm:items-center sm:justify-between space-y-3 sm:space-y-0 relative z-20">
            <div class="flex items-center flex-1 max-w-md">
                <label for="search" class="sr-only">ค้นหา</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 transition" placeholder="ค้นหาจากชื่อช่องแชท...">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto relative z-10">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ชื่อช่องแชท</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ประเภท</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ข้อความล่าสุด</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">คู่สนทนา</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">อัปเดตเมื่อ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($channels as $channel)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition" wire:key="channel-{{ $channel->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $channel->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $channel->title }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $channel->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($channel->is_direct)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                        แชทส่วนตัว (1-on-1)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300">
                                        แชทกลุ่ม ({{ $channel->participants_count }} คน)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">
                                @if($channel->latestConversations->first())
                                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $channel->latestConversations->first()->author?->name ?? 'System' }}:</span> 
                                    {{ Str::limit($channel->latestConversations->first()->content, 40) }}
                                @else
                                    <span class="italic text-gray-400">ไม่มีข้อความ</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach($channel->participants->take(3) as $participant)
                                        <div class="relative z-0" title="{{ $participant->author?->name }}">
                                            <x-backend.avatar :src="$participant->author?->profile_image" :name="$participant->author?->name ?? '?'" size="sm" border ring="ring-white dark:ring-gray-800"/>
                                        </div>
                                    @endforeach
                                    @if($channel->participants_count > 3)
                                        <div class="relative z-10 inline-flex items-center justify-center w-8 h-8 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-200 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">
                                            +{{ $channel->participants_count - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $channel->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('backend.messenger.show', $channel->id) }}" class="text-orange-600 hover:text-orange-900 dark:text-orange-500 dark:hover:text-orange-400 transition" wire:navigate>
                                    ดูประวัติแชท
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p>ไม่พบรายการช่องแชท</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($channels->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $channels->links() }}
            </div>
        @endif
    </div>
</div>
