<x-layouts.main>

<div class="max-w-2xl mx-auto px-4">
    <header 
        class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700 
               bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
        <div class="text-lg font-semibold text-cyan-400">Hashtag</div>
        <div class="w-1/2 lg:w-1/3">
            <input 
                type="search" 
                placeholder="Search..." 
                class="w-full py-2 px-4 bg-slate-800 border border-slate-700 rounded-lg text-white 
                       placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-400"
            >
        </div>
    </header>

    <div class="flex justify-between flex-wrap gap-4 items-center px-4 my-5 md:my-10">
        <span class="text-white text-lg font-semibold">Manage Hashtag</span>
        <form action="{{ route('tags.store') }}" method="POST" class="flex gap-2 ">
            @csrf
            <input
                type="text"
                id="name" name="name" placeholder="Nama hashtag..." required
                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150"
            >
            <button class="bg-gray-700 hover:bg-gray-600 transition-colors flex gap-2 items-center text-white font-medium border border-gray-500 rounded-lg px-4 py-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-plus w-5 h-5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.929 4.929a10 10 0 1 1 14.141 14.141a10 10 0 0 1 -14.14 -14.14zm8.071 4.071a1 1 0 1 0 -2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0 -2h-2v-2z" /></svg></button>
        </form>
    </div>


    <div class="w-full mx-auto mb-20 px-4" 
        x-data="{ deleteTag(id) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: 'Hashtag akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
            }).then((result) => {
                if (result.isConfirmed) {
                    $refs['form-' + id].submit();
                }
            });
        }}">
        <div class="w-full flex flex-wrap gap-2 mt-4">
            @forelse ($tags as $tag)
                <span class="inline-flex items-center px-4 py-2 text-sm font-normal bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white rounded-2xl">
                    <span>#{{ $tag->name }}</span>
                    <button
                        type="button"
                        x-on:click="deleteTag({{ $tag->id }})"
                        class="ml-1.5 -mr-0.5 inline-flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full text-white bg-rose-500 p-1"
                    >
                        <svg class="h-2.5 w-2.5" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"></path>
                        </svg>
                    </button>

                    <form x-ref="form-{{ $tag->id }}" method="POST" action="{{ route('tags.destroy', $tag->id) }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </span>
            @empty
                <span class="text-gray-500">Tidak Ada Hashtag</span>
            @endforelse
        </div>
    </div>


</div>

</x-layouts.main>