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

    <!-- Main Content Area: Tab Navigation & Content -->
    <div x-data="{ activeTab: 'general' }">
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    ข้อมูลทั่วไป (General)
                </button>
                <button @click="activeTab = 'points'" :class="activeTab === 'points' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    จัดการคะแนน (Points)
                </button>
                <button @click="activeTab = 'badges'" :class="activeTab === 'badges' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    ตราสัญลักษณ์ (Badges)
                </button>
            </nav>
        </div>

        <!-- Tab Panels -->
        <div>
            <!-- TAB: General -->
            <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Sidebar: User Identity & Basic Info -->
                    <div class="space-y-6">
                        
                        <!-- User Profile Card -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="h-32 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                                @if($user->getCoverImage())
                                    <img src="{{ $user->getCoverImage() }}" class="w-full h-full object-cover" alt="Cover Photo">
                                @else
                                    <div class="w-full h-full bg-gradient-to-r from-orange-400 to-orange-600 flex items-center justify-center opacity-90">
                                        <svg class="w-12 h-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="px-6 pb-6 relative">
                                <div class="-mt-12 flex justify-center mb-4">
                                    <x-backend.avatar :src="$user->getAvatar(128)" :name="$user->name" size="h-24 w-24" class="border-4 border-white dark:border-gray-800 bg-white shadow-md shadow-black/5" />
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
                                    <dt class="text-gray-500 dark:text-gray-400 mb-1">วัน/เดือน/ปีเกิด</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 mb-1">เบอร์โทรศัพท์</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $user->mobile_phone ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 mb-1">ที่อยู่/พิกัด (Location)</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $user->location ?: '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <dt class="text-gray-500 dark:text-gray-400 mb-1">ประวัติย่อ (Biography)</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white italic">{{ $user->biography ?: 'ไม่มีประวัติย่อ' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400 mb-1">ธีมแอปพลิเคชัน</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white capitalize">{{ $user->theme ?: 'system' }}</dd>
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
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">คะแนนสะสม</dt>
                                <dd class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($user->availablePoints()) }}</dd>
                            </div>
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ประวัติธุรกรรม</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->transactions->count()) }}</dd>
                            </div>
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ผลงาน</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->works->count()) }}</dd>
                            </div>
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">กระทู้</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->posts->count()) }}</dd>
                            </div>
                        </div>

                        <!-- Reputation & Stats -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">ชื่อเสียง และ สถิติการทำงาน (Reputation & Stats)</h3>
                                </div>
                                @if($user->reputation)
                                    <div class="flex space-x-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                            ระดับความน่าเชื่อถือ: {{ $user->reputation->trust_level_label ?? '-' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($user->reputation)
                                <!-- Scores Section -->
                                <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wider flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                        คะแนนความพึงพอใจ (Scores)
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-blue-200 dark:border-blue-900/50 shadow-sm">
                                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">ภาพรวมคะแนน</div>
                                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($user->reputation->overall_score ?? 0, 1) }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">คุณภาพงาน</div>
                                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($user->reputation->quality_score ?? 0, 1) }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">ความตรงเวลา</div>
                                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($user->reputation->timeliness_score ?? 0, 1) }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">การสื่อสาร</div>
                                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($user->reputation->communication_score ?? 0, 1) }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">มืออาชีพ</div>
                                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($user->reputation->professionalism_score ?? 0, 1) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistics Section -->
                                <div class="p-6">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wider flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                        สถิติการรับงาน (Job Statistics)
                                    </h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">งานที่สำเร็จ</dt>
                                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($user->reputation->total_completed_jobs ?? 0) }} <span class="text-xs font-normal text-gray-500">งาน</span></dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">อัตราความสำเร็จ</dt>
                                            <dd class="mt-1 text-lg font-semibold {{ ($user->reputation->completion_rate ?? 0) >= 80 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">{{ number_format($user->reputation->completion_rate ?? 0, 1) }}%</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">งานที่ยกเลิก</dt>
                                            <dd class="mt-1 text-lg font-semibold text-red-600 dark:text-red-400">{{ number_format($user->reputation->total_cancelled_jobs ?? 0) }} <span class="text-xs font-normal text-gray-500">งาน</span></dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">ลูกค้ากลับมาใช้ซ้ำ</dt>
                                            <dd class="mt-1 text-lg font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($user->reputation->repeat_customer_count ?? 0) }} <span class="text-xs font-normal text-gray-500">คน</span></dd>
                                        </div>
                                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">รีวิวที่ได้รับทั้งหมด</dt>
                                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($user->reputation->total_reviews ?? 0) }} <span class="text-xs font-normal text-gray-500">รายการ</span></dd>
                                        </div>
                                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">อัตราการตอบแชท</dt>
                                            <dd class="mt-1 text-lg font-semibold text-blue-600 dark:text-blue-400">{{ number_format($user->reputation->response_rate ?? 0, 1) }}%</dd>
                                        </div>
                                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">ความเร็วในการตอบกลับ</dt>
                                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($user->reputation->avg_response_minutes ?? 0) }} <span class="text-xs font-normal text-gray-500">นาที</span></dd>
                                        </div>
                                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">คะแนนประพฤติสะสม</dt>
                                            <dd class="mt-1 text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ number_format($user->reputation->total_points_earned ?? 0) }} <span class="text-xs font-normal text-gray-500">RP</span></dd>
                                        </div>
                                    </div>
                                    
                                    <!-- Badges Summary inside Reputation -->
                                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">ตราสัญลักษณ์เด่น (Badges)</h4>
                                            <button @click="activeTab = 'badges'" class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition cursor-pointer">
                                                ดูการจัดการตราทั้งหมด
                                            </button>
                                        </div>
                                        @if($user->badges->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($user->badges as $badge)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-300 shadow-sm">
                                                        {{ $badge->label ?? 'Badge' }} (Lv.{{ $badge->badge_level }})
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">บัญชีนี้ยังไม่ได้รับตราสัญลักษณ์พิเศษ</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="p-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">ยังไม่มีประวัติชื่อเสียง</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ผู้ใช้รายนี้ยังไม่ได้สร้างประวัติรับงาน หรือยังไม่มีการคำนวณคะแนน</p>
                                </div>
                            @endif
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

            <!-- TAB: Points -->
            <div x-show="activeTab === 'points'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                @livewire('backend.user.partials.points-manager', ['user' => $user])
            </div>

            <!-- TAB: Badges -->
            <div x-show="activeTab === 'badges'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                @livewire('backend.user.partials.badges-manager', ['user' => $user])
            </div>

        </div>
    </div>
</div>
