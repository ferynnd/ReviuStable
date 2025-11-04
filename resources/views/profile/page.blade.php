<x-layouts.main>

<div class="max-w-2xl mx-auto px-4">
    <header
        class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700
               bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
        <div class="text-lg font-semibold text-cyan-400">Profile</div>
    </header>

    <section class="flex flex-col sm:flex-row sm:items-start sm:space-x-4 mt-10 space-y-4 gap-2 lg:gap-5 sm:space-y-0">

        <img
            src="{{ $user->image ? asset('storage/' . $user->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->fullname).'&background=0D8ABC&color=fff' }}"
            class="w-20 h-20 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-full object-cover border-2 border-slate-700 shadow mx-auto sm:mx-0"
            alt="{{ $user->fullname }}"
        >

        <div class="flex flex-col items-center sm:items-start text-center sm:text-left">
            <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-slate-100">{{ $user->fullname }}</h2>
            <p class="text-slate-400 text-sm sm:text-base">{{ '@'.$user->username }}</p>
            <p class="mt-3 text-white text-sm sm:text-base max-w-md">
                {{ $user->bio }}
            </p>
        </div>

        @if(auth()->id() === $user->id)
            <div x-data="{}" class="flex flex-row sm:self-start sm:ml-auto gap-2 w-full sm:w-auto justify-between sm:justify-end">

                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center justify-center gap-2
                        text-slate-100 bg-gray-800 text-sm border border-slate-600 px-3 py-2 rounded-md
                        hover:border-cyan-400 hover:text-cyan-400 transition w-1/2 sm:w-auto text-center">
                    Edit
                </a>

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

        <div class="mt-6">
            <div x-show="activeTab === 'feeds'" x-transition>
                <div class="grid grid-cols-3 gap-3">

                    @if (Auth::id() === $user->id)
                    <a href="{{ route('profile.drafts', ['username' => $user->username, 'type' => 'feed']) }}"
                        class="aspect-[4/5] flex items-center justify-center backdrop-blur-lg bg-slate-700/40 text-white rounded-md cursor-pointer hover:ring-2 hover:ring-sky-400 transition"
                    >
                        <span class="text-md font-semibold">
                            DRAFT
                            <span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-xs font-bold text-white bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 rounded-full">
                                {{ $draftCounts["feeds"] }}
                            </span>
                        </span>
                    </a>
                    @endif


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


            <div x-data="{ showDrafts: false }">
                <div x-show="activeTab === 'reels'" x-transition>
                    <div class="grid grid-cols-3 gap-3">
                        @if (Auth::id() === $user->id)
                        <a href="{{ route('profile.drafts', ['username' => $user->username, 'type' => 'reel']) }}"
                            class="aspect-[4/5] flex items-center justify-center backdrop-blur-lg bg-slate-700/40 text-white rounded-md cursor-pointer hover:ring-2 hover:ring-sky-400 transition"
                        >
                            <span class="text-md font-semibold">
                                DRAFT
                                <span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-xs font-bold text-white bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 rounded-full">
                                    {{ $draftCounts["reels"] }}
                                </span>
                            </span>
                        </a>
                        @endif

                        @foreach ($reels as $reel)
                            @php
                                $media = $reel->getFirstMedia($reel->getMediaCollectionName($reel->content_type));
                                $mediaUrl = $media ? $media->getUrl() : null;
                                $isVideo = $media && str_starts_with($media->mime_type, 'video/');
                            @endphp

                            <a href="{{ route('post.detail', $reel->slug) }}"
                                class="aspect-[4/5] bg-slate-800 rounded-md overflow-hidden hover:ring-2 hover:ring-sky-500 transition relative group">

                                @if ($isVideo)
                                    <video
                                        src="{{ $mediaUrl }}"
                                        muted
                                        playsinline
                                        class="w-full h-full object-cover group-hover:brightness-75 transition"
                                    ></video>

                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-white/90"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                @else
                                    <img src="{{ $mediaUrl ?: 'https://via.placeholder.com/300x300?text=No+Preview' }}"
                                        alt="Reel image"
                                        class="w-full h-full object-cover" />
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>


            <div x-show="activeTab === 'carousel'" x-transition>
                <div class="grid grid-cols-3 gap-3">

                    @if (Auth::id() === $user->id)
                    <a href="{{ route('profile.drafts', ['username' => $user->username, 'type' => 'carousel']) }}"
                        class="aspect-[4/5] flex items-center justify-center backdrop-blur-lg bg-slate-700/40 text-white rounded-md cursor-pointer hover:ring-2 hover:ring-sky-400 transition"
                    >
                        <span class="text-md font-semibold">
                            DRAFT
                            <span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-xs font-bold text-white bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 rounded-full">
                                {{ $draftCounts["carousels"] }}
                            </span>
                        </span>

                    </a>
                    @endif

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

                    @if (Auth::id() === $user->id)
                    <a href="{{ route('profile.drafts', ['username' => $user->username, 'type' => 'story']) }}"
                        class="aspect-[4/5] flex items-center justify-center backdrop-blur-lg bg-slate-700/40 text-white rounded-md cursor-pointer hover:ring-2 hover:ring-sky-400 transition"
                    >
                        <span class="text-md font-semibold">
                            DRAFT
                            <span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-xs font-bold text-white bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 rounded-full">
                                {{ $draftCounts["stories"] }}
                            </span>
                        </span>
                    </a>
                    @endif

                    @foreach ($stories as $story)
                         @php
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
