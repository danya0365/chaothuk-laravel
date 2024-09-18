<div class="chat-header p-4 flex flex-row flex-none justify-between items-center shadow">
    <div class="flex items-center justify-center space-x-2">
        <a href="{{ route('home') }}">
            <span class=" text-teal-600 hover:text-teal-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
            </span>
        </a>
        <div class="w-12 h-12 mr-4 relative flex flex-shrink-0">
            <img class="shadow-md rounded-full w-full h-full object-cover"
                src="{{ $messengerChannel->getAdminAvatar() }}" alt="" />
        </div>
        <div class="text-sm">
            <p class="font-bold">{{ $messengerChannel->getAdminName() }}</p>
            <p>{{ $messengerChannel->getAdminLastSeen() }}</p>
        </div>
    </div>
</div>
