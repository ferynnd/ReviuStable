<x-layouts.main>

<div class="max-w-2xl mx-auto">

    <header
        class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700
               bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
        <div class="text-lg font-semibold text-cyan-400">Home</div>
        <div class="w-1/2 lg:w-1/3">
            <form action="{{ route('home') }}" method="GET">
                <input
                    type="search"
                    name="search"
                    value="{{ $request->search ?? '' }}"
                    placeholder="Search.."
                    class="w-full py-2 px-4 bg-slate-800 border border-slate-700 rounded-lg text-white
                            placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-400"
                >
            </form>
        </div>
    </header>

    <main class="pt-5 pb-16 px-4 sm:px-4 mx-auto max-w-md">
        @if($posts->isNotEmpty())
            @foreach($posts as $post)
                @include('components.post-card', ['post' => $post])
            @endforeach
        @endif
    </main>
</div>


</x-layouts.main>
