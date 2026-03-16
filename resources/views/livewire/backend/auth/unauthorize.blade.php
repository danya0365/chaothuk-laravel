<div class="w-full text-center">
    <div class="mb-6 flex justify-center">
        <svg class="w-20 h-20 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">Access Denied</h2>
    
    <p class="text-gray-600 dark:text-gray-400 mb-8 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4 text-left text-sm rounded-r-lg">
        บัญชีของคุณไม่มีสิทธิ์เข้าถึงระบบจัดการส่วนกลาง (Backend) <br>
        กรุณากลับไปยังหน้าผู้ใช้งานเพื่อใช้งานระบบตามปกติ
    </p>

    <div class="flex flex-col space-y-4">
        <!-- Back to Frontend -->
        <a href="/" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition duration-150 ease-in-out">
            กลับสู่หน้าผู้ใช้งาน (Frontend)
        </a>

        <!-- Log Out Form -->
        <form method="POST" action="{{ route('frontend.auth.logout') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition duration-150 ease-in-out">
                ออกจากระบบด้วยบัญชีนี้
            </button>
        </form>
    </div>
</div>
