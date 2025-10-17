<nav class="
    fixed 
    bottom-0 
    left-1/2 
    transform -translate-x-1/2 
    z-50 
    w-[calc(100%-1rem)] 
    max-w-lg 
    h-16 
    mb-3 
    mx-auto 
    bg-gray-800 
    rounded-xl 
    shadow-2xl shadow-gray-900/50
    md:hidden 
">
    <div class="flex h-full justify-around mx-auto">
        
        <a href="#" class="inline-flex flex-col items-center justify-center px-5 group">
            <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-sky-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-sky-400">Home</span>
        </a>

        <a href="#" class="inline-flex flex-col items-center justify-center px-5 group">
            <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-sky-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-sky-400">Calendar</span>
        </a>
        
        @role('staff')
        <a href="{{route('post.index')}}" class="inline-flex flex-col items-center justify-center px-5 group">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rocket w-6 h-6 mb-1 text-gray-400 group-hover:text-sky-400"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" /><path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" /><path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
            <span class="text-xs text-gray-400 group-hover:text-sky-400">Post</span>
        </a>
        @endrole
        
        @role('superadmin')
        <a href="#" class="inline-flex flex-col items-center justify-center px-5 group">
            <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-sky-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-sky-400">Users</span>
        </a>
        @endrole

        <a href="#" class="inline-flex flex-col items-center justify-center px-5 group">
            <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-sky-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.25a.75.75 0 0 1-.75-.75v-1.5a4.5 4.5 0 0 1 8.525-2.126M19.5 19.5a.75.75 0 0 1-.75-.75v-1.5a4.5 4.5 0 0 0-8.525-2.126" />
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-sky-400">Profile</span>
        </a>
    </div>
</nav>