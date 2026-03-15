<div class="p-6 sm:p-10 max-w-4xl mx-auto space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ตั้งค่าระบบ (Configurations)</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">จัดการข้อมูลพื้นฐานและการทำงานหลักของระบบที่ส่งผลต่อหน้าเว็บไซต์และแอปพลิเคชัน</p>
        </div>
        <div class="flex">
            <button type="button" wire:click="save" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <svg wire:loading.remove wire:target="save" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                บันทึกการตั้งค่า
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800 transition-all">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800 transition-all">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18C5.58172 18 2 14.4183 2 10C2 5.58172 5.58172 2 10 2C14.4183 2 18 5.58172 18 10C18 14.4183 14.4183 18 10 18ZM8.70711 7.29289C8.31658 6.90237 7.68342 6.90237 7.29289 7.29289C6.90237 7.68342 6.90237 8.31658 7.29289 8.70711L8.58579 10L7.29289 11.2929C6.90237 11.6834 6.90237 12.3166 7.29289 12.7071C7.68342 13.0976 8.31658 13.0976 8.70711 12.7071L10 11.4142L11.2929 12.7071C11.6834 13.0976 12.3166 13.0976 12.7071 12.7071C13.0976 12.3166 13.0976 11.6834 12.7071 11.2929L11.4142 10L12.7071 8.70711C13.0976 8.31658 13.0976 7.68342 12.7071 7.29289C12.3166 6.90237 11.6834 6.90237 11.2929 7.29289L10 8.58579L8.70711 7.29289Z" clip-rule="evenodd" /></svg>
                <p class="ml-3 text-sm font-medium text-red-800 dark:text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Data Table / Forms -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden relative">
        <form wire:submit="save" class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($configs as $config)
                <div class="p-6 md:p-8 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors flex flex-col md:flex-row md:items-start md:space-x-8">
                    <!-- Label Section -->
                    <div class="md:w-1/3 mb-4 md:mb-0">
                        <label for="{{ $config->slug }}" class="block text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $config->name }}
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded inline-block">
                            {{ $config->slug }}
                        </p>
                    </div>

                    <!-- Input Section -->
                    <div class="md:w-2/3">
                        @if ($config->value_type === 'text' || $config->value_type === \App\Enums\ConfigurationValueType::TEXT->value)
                            <input type="text" wire:model="settings.{{ $config->slug }}" id="{{ $config->slug }}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        
                        @elseif ($config->value_type === 'url' || $config->value_type === \App\Enums\ConfigurationValueType::URL->value)
                            <input type="url" wire:model="settings.{{ $config->slug }}" id="{{ $config->slug }}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="https://...">

                        @elseif ($config->value_type === 'textarea' || $config->value_type === \App\Enums\ConfigurationValueType::TEXTAREA->value)
                            <textarea wire:model="settings.{{ $config->slug }}" id="{{ $config->slug }}" rows="3" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>

                        @elseif ($config->value_type === 'option' || $config->value_type === \App\Enums\ConfigurationValueType::OPTION->value)
                            <select wire:model="settings.{{ $config->slug }}" id="{{ $config->slug }}" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md appearance-none shadow-sm">
                                <option value="">-- เลือก --</option>
                                @if(is_array($config->value_options))
                                    @foreach($config->value_options as $option)
                                        <option value="{{ $option }}">{{ __($option) }}</option>
                                    @endforeach
                                @endif
                            </select>

                        @elseif ($config->value_type === 'boolean' || $config->value_type === \App\Enums\ConfigurationValueType::BOOLEAN->value)
                            <div class="flex items-center">
                                <button type="button" wire:click="$set('settings.{{ $config->slug }}', {{ empty($settings[$config->slug]) || $settings[$config->slug] == '0' ? '1' : '0' }})" class="{{ !empty($settings[$config->slug]) && $settings[$config->slug] != '0' ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }} relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" role="switch">
                                    <span class="{{ !empty($settings[$config->slug]) && $settings[$config->slug] != '0' ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                </button>
                                <span class="ml-3 font-medium text-sm text-gray-900 dark:text-gray-300">
                                    {{ !empty($settings[$config->slug]) && $settings[$config->slug] != '0' ? 'เปิดใช้งาน' : 'ปิดการใช้งาน' }}
                                </span>
                            </div>

                        @else
                            <input type="text" wire:model="settings.{{ $config->slug }}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @endif

                        @error('settings.'.$config->slug)
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    ไม่พบข้อมูลคีย์การตั้งค่าในระบบ
                </div>
            @endforelse
            
            @if(count($configs) > 0)
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-end rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    บันทึกการตั้งค่าทั้งหมด
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
