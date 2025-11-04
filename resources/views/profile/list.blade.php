<x-layouts.main>

<div class="max-w-2xl mx-auto">


    <header
        class="sticky top-0 z-50 h-20 flex items-center justify-start gap-3 px-6 border-b border-slate-700
        bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">

        <a onclick="history.back()"
           class="p-2 rounded-lg hover:bg-cyan-400/20 text-white transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-chevron-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M15 6l-6 6l6 6" />
            </svg>
        </a>

        <span class="text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-minus-vertical">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M12 5v14" />
            </svg>
        </span>

        <div class="text-lg font-semibold text-cyan-400">
            List Draft {{ ucfirst($type) }}
        </div>
    </header>



    <main class="py-8 px-4 sm:px-4 mx-auto max-w-md mb-12">
        @if($posts->isNotEmpty())
            @foreach($posts as $post)
                @include('components.post-card', ['post' => $post])
            @endforeach
        @else
            <p class="text-slate-400">Tidak ada post pada draft ini.</p>
        @endif
    </main>
</div>


</x-layouts.main>
