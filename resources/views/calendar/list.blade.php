<x-layouts.main>

<div class="max-w-2xl mx-auto">

    <header
        class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700
        bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
        <div class="text-lg font-semibold text-cyan-400">List Content at <span  class="text-white "> {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }} </span></div>

    </header>

    <main class="py-8 px-4 sm:px-4 mx-auto max-w-md mb-12">
        @if($posts->isNotEmpty())
            @foreach($posts as $post)
                @include('components.post-card', ['post' => $post])
            @endforeach
        @else
            <p class="text-slate-400">Tidak ada post pada tanggal ini.</p>
        @endif
    </main>
</div>


</x-layouts.main>
