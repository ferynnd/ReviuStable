<x-layouts.main>

<div class="max-w-2xl mx-auto px-4">
    <header 
        class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700 
               bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
        <div class="text-lg font-semibold text-cyan-400">Profile</div>
    </header>

    <section class="flex flex-col sm:flex-row sm:items-start sm:space-x-4 mt-10 space-y-4 gap-2 lg:gap-5 sm:space-y-0">
        <!-- Gambar profil -->
        <img 
            src="{{ $user->image ? asset('storage/' . $user->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->fullname).'&background=0D8ABC&color=fff' }}" 
            class="w-20 h-20 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-full border-2 border-slate-700 shadow mx-auto sm:mx-0"
            alt="{{ $user->fullname }}"
        >

        <!-- Info user -->
        <div class="flex flex-col items-center sm:items-start text-center sm:text-left">
            <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-slate-100">{{ $user->fullname }}</h2>
            <p class="text-slate-400 text-sm sm:text-base">{{ '@'.$user->username }}</p>
            <p class="mt-3 text-white text-sm sm:text-base max-w-md">
                {{ $user->bio }}
            </p>
        </div>

          @if(auth()->id() === $user->id)
    <div x-data="{}" class="flex flex-row sm:self-start sm:ml-auto gap-2 w-full sm:w-auto justify-between sm:justify-end">
        
        {{-- Tombol Edit --}}
        <a href="{{ route('profile.edit') }}" 
            class="inline-flex items-center justify-center gap-2 
                   text-slate-100 bg-gray-800 text-sm border border-slate-600 px-3 py-2 rounded-md 
                   hover:border-cyan-400 hover:text-cyan-400 transition w-1/2 sm:w-auto text-center">
            Edit
        </a>

        {{-- Tombol Logout --}}
        <form method="POST" action="{{ route('logout') }}" 
              x-ref="logoutForm" 
              class="w-1/2 sm:w-auto"
              @submit.prevent>
            @csrf
            <button 
                class="inline-flex items-center justify-center gap-2 
                       text-red-400 bg-gray-800 text-sm border border-slate-600 px-3 py-2 rounded-md 
                       hover:border-red-400 hover:text-red-300 transition w-full sm:w-auto block md:hidden text-center"
                type="button"
                @click="Swal.fire({
                        title: 'Anda yakin?',
                        text: 'Anda akan keluar dari sesi ini.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3B82F6', 
                        cancelButtonColor: '#EF4444', 
                        confirmButtonText: 'Ya, Logout!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $refs.logoutForm.submit();
                        }
                    })
                ">
                Logout
            </button>
        </form>
    </div>
    @endif
    </section>



    <div 
        x-data="{ activeTab: 'feeds' }" 
        class="mt-10 w-full ">
        <nav class="flex justify-evenly pb-5 border-b border-slate-700 space-x-8 text-slate-400">
            <button @click="activeTab = 'feeds'" 
                :class="activeTab === 'feeds' ? 'text-cyan-400 border-b-2 border-cyan-400 pb-2' : 'hover:text-slate-200 pb-2'">Feeds</button>
            <button @click="activeTab = 'reels'" 
                :class="activeTab === 'reels' ? 'text-cyan-400 border-b-2 border-cyan-400 pb-2' : 'hover:text-slate-200 pb-2'">Reels</button>
            <button @click="activeTab = 'carousel'" 
                :class="activeTab === 'carousel' ? 'text-cyan-400 border-b-2 border-cyan-400 pb-2' : 'hover:text-slate-200 pb-2'">Carousel</button>
            <button @click="activeTab = 'story'" 
                :class="activeTab === 'story' ? 'text-cyan-400 border-b-2 border-cyan-400 pb-2' : 'hover:text-slate-200 pb-2'">Story</button>
        </nav>

        {{-- Tab Content --}}
        <div class="mt-6">
            {{-- Feeds --}}
            <div x-show="activeTab === 'feeds'" x-transition>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($feeds as $feed)
                         @php
                            // Ambil media pertama (index 0)
                            $media = $feed->getFirstMedia($feed->getMediaCollectionName($feed->content_type));
                            $imageUrl = $media ? $media->getUrl() : 'https://via.placeholder.com/300x300?text=No+Image';
                        @endphp

            <a href="{{route('post.detail', $feed->slug)}}" class="aspect-[4/5] bg-slate-800 rounded-md overflow-hidden hover:ring-2 hover:ring-sky-500 transition">
                <img 
                    src="{{ $imageUrl }}" 
                    alt="Feed image" 
                    class="w-full h-full object-cover"
                >
            </a>
                    @endforeach
                </div>
            </div>

            {{-- Reels --}}
            <div x-show="activeTab === 'reels'" x-transition>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($reels as $reel)
                         @php
                            // Ambil media pertama (index 0)
                            $media = $reel->getFirstMedia($reel->getMediaCollectionName($reel->content_type));
                            $imageUrl = $media ? $media->getUrl() : 'https://via.placeholder.com/300x300?text=No+Image';
                        @endphp

                        <a href="{{route('post.detail', $reel->slug)}}" class="aspect-[4/5] bg-slate-800 rounded-md overflow-hidden hover:ring-2 hover:ring-sky-500 transition">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="Feed image" 
                                class="w-full h-full object-cover"
                            >
                        </a>
                    @endforeach
                </div>
            </div>

            <div x-show="activeTab === 'carousel'" x-transition>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($carousels as $item)
                         @php
                            // Ambil media pertama (index 0)
                            $media = $item->getFirstMedia($item->getMediaCollectionName($item->content_type));
                            $imageUrl = $media ? $media->getUrl() : 'https://via.placeholder.com/300x300?text=No+Image';
                        @endphp
                        <a href="{{route('post.detail', $item->slug)}}" class="aspect-[4/5] relative bg-slate-800 rounded-md overflow-hidden hover:ring-2 hover:ring-sky-500 transition">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt="Feed image" 
                                class="w-full h-full object-cover z-10 hover:scale-110 transition-transform"
                            >
                            @if ($item->status === 'revision')
                                <span class="absolute z-20 top-2 right-2 text-xs font-medium px-3 py-1 rounded-full text-white bg-rose-500 ml-2">
                                    Revision
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div x-show="activeTab === 'story'" x-transition>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($stories as $story)
                         @php
                // Ambil media pertama (index 0)
                $media = $story->getFirstMedia($story->getMediaCollectionName($story->content_type));
                $imageUrl = $media ? $media->getUrl() : 'https://via.placeholder.com/300x300?text=No+Image';
            @endphp

            <a href="{{route('post.detail', $story->slug)}}" class="aspect-[4/5] bg-slate-800 rounded-md overflow-hidden hover:ring-2 hover:ring-sky-500 transition">
                <img 
                    src="{{ $imageUrl }}" 
                    alt="Feed image" 
                    class="w-full h-full object-cover"
                >
            </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

</x-layouts.main>