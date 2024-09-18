<div class="chat-header p-4 flex flex-row flex-none justify-between items-center shadow">
    <div class="flex items-center justify-center space-x-2">
        <a href="{{ route('backend.messenger-channels.index') }}">
            <span class=" text-teal-600 hover:text-teal-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
            </span>
        </a>
        @php
            $participant = $messengerChannel->getCustomer();
            $customer = null;
            $merchant = null;
            if ($participant) {
                $customer = $participant->author?->customer;
                $merchant = $participant->author?->merchant;
            }
        @endphp
        @if ($customer)
            <div class="text-sm">
                <p class="font-bold">ลูกค้า: {{ $customer->getPersonInfo()->getFullName() }}</p>
                <p>{{ $messengerChannel->getCustomerLastSeen() }}</p>
            </div>
        @endif
        @if ($merchant)
            <div class="text-sm">
                <p class="font-bold">ร้านค้า: {{ $merchant->name }}</p>
                <p>{{ $messengerChannel->getCustomerLastSeen() }}</p>
            </div>
        @endif
        @if (!$customer && !$merchant)
            <div class="text-sm flex items-center justify-start">
                <p class="font-bold">{{ $messengerChannel->getCustomerName() }}</p>
            </div>
        @endif
    </div>
</div>
