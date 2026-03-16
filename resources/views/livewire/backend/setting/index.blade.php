<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                การตั้งค่าระบบ (System Settings)
            </h2>
            <p class="text-gray-400 text-sm mt-1">
                จัดการการตั้งค่าพื้นฐานของแพลตฟอร์ม, ค่าธรรมเนียม, และข้อมูลติดต่อ
            </p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-xl flex items-start gap-3 text-emerald-400">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl overflow-hidden p-2 flex flex-col gap-1">
                <button 
                    wire:click="switchTab('general')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'general' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'text-gray-400 hover:bg-slate-700/50 hover:text-gray-200 border border-transparent' }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 0H4.5m-1.5 7.5h18m-18 7.5h18m-18-7.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 0H4.5m10.5 7.5c0 .828-.672 1.5-1.5 1.5s-1.5-.672-1.5-1.5.672-1.5 1.5-1.5 1.5.672 1.5 1.5Zm0 0H4.5=" />
                    </svg>
                    ข้อมูลทั่วไป (General)
                </button>
                
                <button 
                    wire:click="switchTab('payment')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'payment' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'text-gray-400 hover:bg-slate-700/50 hover:text-gray-200 border border-transparent' }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    การเงินและค่าธรรมเนียม
                </button>

                <button 
                    wire:click="switchTab('social')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'social' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'text-gray-400 hover:bg-slate-700/50 hover:text-gray-200 border border-transparent' }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                    </svg>
                    ช่องทางติดต่อ & โซเชียล
                </button>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1">
            <form wire:submit="save" class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 shadow-sm">
                
                <!-- General Tab -->
                @if($activeTab === 'general')
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-200 border-b border-slate-700/50 pb-3">ข้อมูลทั่วไป (General Configuration)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">ชื่อเว็บไซต์ (Site Name)</label>
                                <input type="text" wire:model="settings.site_name" class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600" placeholder="e.g. Chaothuk">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">อีเมลติดต่อระดับแพลตฟอร์ม</label>
                                <input type="email" wire:model="settings.contact_email" class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-400 mb-2">คำอธิบายเว็บไซต์ (Site Description / SEO)</label>
                                <textarea wire:model="settings.site_description" rows="3" class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600"></textarea>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment & Fees Tab -->
                @if($activeTab === 'payment')
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-200 border-b border-slate-700/50 pb-3">การเงินและค่าธรรมเนียม (Payment & Fees)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">เปอร์เซ็นต์หักบัญชีแพลตฟอร์ม (%)</label>
                                <div class="relative">
                                    <input type="number" wire:model="settings.platform_fee_percent" class="w-full pl-4 pr-10 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">ค่าธรรมเนียมที่หักจากผู้รับจ้างเมื่อจบงาน</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">ขั้นต่ำในการถอนเงิน (บาท)</label>
                                <div class="relative">
                                    <input type="number" wire:model="settings.minimum_withdrawal" class="w-full pl-4 pr-12 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">THB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Social Tab -->
                @if($activeTab === 'social')
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-200 border-b border-slate-700/50 pb-3">ช่องทางติดต่อ & โซเชียล (Social Links)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Facebook Page URL</label>
                                <input type="url" wire:model="settings.facebook_url" class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Line Official Account URL</label>
                                <input type="url" wire:model="settings.line_url" class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">เบอร์โทรศัพท์ (Help Center)</label>
                                <input type="text" wire:model="settings.contact_phone" class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-600">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-8 pt-6 border-t border-slate-700/50 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-xl transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)] flex items-center gap-2">
                        <svg class="w-4 h-4" wire:loading.remove wire:target="save" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                        </svg>
                        <svg class="w-4 h-4 animate-spin" wire:loading wire:target="save" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
