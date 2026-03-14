<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div class="flex items-center space-x-4">
            <a href="{{ route('backend.users.index') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">รายละเอียดผู้ใช้งาน</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ภาพรวมข้อมูลบัญชีผู้ใช้และกิจกรรมในระบบ</p>
            </div>
        </div>
        <div>
            <a href="{{ route('backend.users.edit', $user->id) }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition duration-150 ease-in-out">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                แก้ไขข้อมูล
            </a>
        </div>
    </div>

    <!-- Main Content Area: Sidebar + Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Sidebar: User Identity & Basic Info -->
        <div class="space-y-6">
            
            <!-- User Profile Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="h-32 bg-gray-200 dark:bg-gray-700 relative">
                    <!-- Cover Image placeholder or actual -->
                    <img src="{{ $user->getCoverImage() }}" class="w-full h-full object-cover">
                </div>
                <div class="px-6 pb-6 relative">
                    <div class="-mt-12 flex justify-center mb-4">
                        <img src="{{ $user->getAvatar(128) }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full border-4 border-white dark:border-gray-800 bg-white object-cover shadow-md">
                    </div>
                    <div class="text-center">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                        
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            @forelse($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                    {{ __('common.role-' . $role->id) ?? $role->name }}
                                </span>
                            @empty
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                    ผู้ใช้ทั่วไป
                                </span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Info Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">ข้อมูลส่วนตัว</h3>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400 mb-1">ชื่อ-นามสกุล</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $user->getFullName() ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400 mb-1">เบอร์โทรศัพท์</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $user->mobile_phone ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400 mb-1">วันที่สมัครสมาชิก</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400 mb-1">อัปเดตล่าสุด</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- All Permissions Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                    สิทธิ์การใช้งานทั้งหมด (Permissions)
                </h3>
                
                <div class="space-y-6">
                    <!-- Role Permissions -->
                    @if($user->roles->count() > 0)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">สิทธิ์จากกลุ่มสิทธิ์ (Role Permissions)</h4>
                            <div class="space-y-4">
                                @foreach($user->roles as $role)
                                    <div>
                                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 mb-2">{{ __('common.role-' . $role->id) ?? $role->name }}</p>
                                        @if($role->permissions->count() > 0)
                                            <ul class="space-y-3 mt-3 ml-2">
                                                @foreach($role->permissions as $permission)
                                                    <li class="flex items-start">
                                                        <span class="flex-shrink-0 flex items-center justify-center h-5 w-5 rounded-full mt-0.5 {{ $permission->pivot->data ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                                                            @if($permission->pivot->data)
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @else
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            @endif
                                                        </span>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium {{ $permission->pivot->data ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-500 line-through' }}">{{ __('common.permission-' . $permission->slug) ?? $permission->name }}</p>
                                                            @if($permission->desc)
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $permission->desc }}</p>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-2 italic">ไม่มีสิทธิ์ที่กำหนดในกลุ่มนี้</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Custom Permissions -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">สิทธิ์เพิ่มเติมเฉพาะบุคคล (Custom)</h4>
                        @if($user->permissions->count() > 0)
                            <ul class="space-y-3">
                                @foreach($user->permissions as $permission)
                                    <li class="flex items-start">
                                                        <span class="flex-shrink-0 flex items-center justify-center h-5 w-5 rounded-full mt-0.5 {{ $permission->pivot->data ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                                                            @if($permission->pivot->data)
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @else
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            @endif
                                                        </span>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium {{ $permission->pivot->data ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-500 line-through' }}">{{ __('common.permission-' . $permission->slug) ?? $permission->name }}</p>
                                                            @if($permission->desc)
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $permission->desc }}</p>
                                                            @endif
                                                        </div>
                                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">ไม่มีสิทธิ์เพิ่มเติมเฉพาะบุคคล</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Main Content: Stats & Activities -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <!-- Points available -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">คะแนนสะสม</dt>
                    <dd class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($user->availablePoints()) }}</dd>
                </div>
                <!-- Transactions count -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ประวัติธุรกรรม (ครั้ง)</dt>
                    <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->transactions->count()) }}</dd>
                </div>
                <!-- Works created -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ผลงาน (ชิ้น)</dt>
                    <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->works->count()) }}</dd>
                </div>
                <!-- Posts authored -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">กระทู้ (โพสต์)</dt>
                    <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->posts->count()) }}</dd>
                </div>
            </div>

            <!-- Reputation & Reviews -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">ชื่อเสียง และ รีวิว</h3>
                    @if($user->reputation)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                            Reputation: {{ number_format($user->reputation->score ?? 0, 1) }}
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">รีวิวที่ได้รับ (Received)</h4>
                            <div class="flex items-center">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white mr-4">{{ $user->reputationReviews->count() }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">รายการ</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">ตราสัญลักษณ์ (Badges)</h4>
                            @if($user->badges->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->badges as $badge)
                                        <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-300 shadow-sm">
                                            🌟 {{ $badge->name ?? 'Badge' }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">ยังไม่มีตราสัญลักษณ์</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Moderation Info -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white text-red-600 dark:text-red-400">ข้อมูลความประพฤติ (Moderation)</h3>
                </div>
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">การถูกรายงาน (Reports)</h4>
                        <p class="text-sm text-gray-900 dark:text-white">ถูกรายงานจำนวน <span class="font-bold {{ $user->reports->count() > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">{{ $user->reports->count() }}</span> ครั้ง</p>
                    </div>
                    @if($user->reports->count() > 0)
                        <button type="button" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">ดูรายละเอียด</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
