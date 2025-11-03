<x-layouts.main>

<div class="max-w-2xl mx-auto px-4">
    <header 
        class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700 
               bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
        <div class="text-lg font-semibold text-cyan-400">User</div>
        <div class="w-1/2 lg:w-1/3">
            <input 
                type="search" 
                placeholder="Search..." 
                class="w-full py-2 px-4 bg-slate-800 border border-slate-700 rounded-lg text-white 
                       placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-400"
            >
        </div>
    </header>

    <div class="flex justify-between items-center my-5 md:my-10">
        <span class="text-white text-lg font-semibold">Manage Users</span>
        <a href="{{route('users.create')}}" class="bg-gray-700 hover:bg-gray-600 transition-colors flex gap-2 items-center text-white font-medium border border-gray-500 rounded-lg px-4 py-2">
            Add <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-plus w-5 h-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.929 4.929a10 10 0 1 1 14.141 14.141a10 10 0 0 1 -14.14 -14.14zm8.071 4.071a1 1 0 1 0 -2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0 -2h-2v-2z" /></svg>
        </a>
    </div>


    <div class="w-full mx-auto mb-20">
        <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-2">
            @if($users->isNotEmpty())
                @foreach($users as $user)
                <x-user-card :user="$user"/>
                @endforeach
            @endif
        </div>
    </div>

</div>

</x-layouts.main>