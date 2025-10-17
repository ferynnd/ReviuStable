<x-layouts.main>

    <div class="max-w-2xl mx-auto">

        <header class="h-20 flex items-center justify-between px-6 border-b border-slate-700 ">
            <div class="text-lg font-semibold text-white">Home</div>
            <div class=" w-1/2 lg:w-1/3">
                <input type="search" placeholder="Search..." class="w-full py-2 px-4 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-400">
            </div>
        </header>

         <main class="py-8 px-4 sm:px-4 w-auto mx-auto lg:w-3/4">

            <h2 class="text-xl font-bold text-sky-400 mb-4 px-2 sm:px-0">FEEDS (Ukuran Standar)</h2>
            
            {{-- Tampilkan 2 Postingan Dummy FEED --}}
            @include('components.post-card', ['type' => 'feed'])
            @include('components.post-card', ['type' => 'feed'])

            <div class="h-8"></div> {{-- Spacer --}}

            <h2 class="text-xl font-bold text-sky-400 mb-4 px-2 sm:px-0">STORIES (Ukuran Vertikal)</h2>

            {{-- Tampilkan 1 Postingan Dummy STORY --}}
            @include('components.post-card', ['type' => 'story'])

        </main>


    </div>

</x-layouts.main>