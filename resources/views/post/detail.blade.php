<x-layouts.main>
    <div class="max-w-2xl mx-auto">
        <header class="h-20 flex items-center gap-7 px-6 border-b border-slate-700 ">
            <a href="{{route('home')}}" class="p-2 rounded-lg hover:bg-cyan-400/80 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
            </a>
            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-minus-vertical text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /></svg></span>
            <div class="text-lg font-semibold text-cyan-400">Detail Post</div>
        </header>


    @push('js')
        <script>
        function postInteractions(slug) {
            return {
                isInteracting: false,
                liked: false,
                likeCount: 0,
                comments: [],
                newComment: '',
                _likeLock: false,
                _commentLock: false,
                shareUrl: '',
                showShareModal: false,
                userLiked : [],
                showLikesModal: false,

                init() {
                    this.loadInitialStatus();
                    this.loadComments();
                },

                async loadInitialStatus() {
                    try {
                        let response = await fetch(`/post/detail/${slug}/status`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        let data = await response.json();


                        this.liked = data.liked;
                        this.likeCount = data.like_count;
                        this.userLiked = data.likes.map(like => like.user);

                    } catch (error) {
                    }
                },

                async toggleLike() {

                    const now = Date.now();
                    if (this._lastLikeClick && (now - this._lastLikeClick) < 1000) {
                        return;
                    }
                    this._lastLikeClick = now;


                    if (this.isInteracting) {
                        return;
                    }

                    this.isInteracting = true;

                    try {
                        const wasLiked = this.liked;
                        this.liked = !this.liked;
                        this.likeCount += this.liked ? 1 : -1;

                        let response = await fetch(`/post/detail/${slug}/like`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({})
                        });

                        if (!response.ok) {
                            // Rollback jika error
                            this.liked = wasLiked;
                            this.likeCount += wasLiked ? -1 : 1;
                            throw new Error(`Like failed with status: ${response.status}`);
                        }

                        let data = await response.json();
                        // Sync dengan data server
                        this.liked = data.liked;
                        this.likeCount = data.like_count;

                        if (data.liked) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Berhasil disukai!',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });
                        }

                    } catch (error) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Gagal memproses like.',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    } finally {
                        setTimeout(() => {
                            this.isInteracting = false;
                        }, 100);
                    }
                },

                async loadComments() {
                    try {
                        let response = await fetch(`/post/detail/${slug}/comments`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        this.comments = await response.json();
                    } catch (error) {
                        console.error('Error loading comments:', error);
                    }
                },

                async submitComment() {

                    const now = Date.now();
                    if (this._lastLikeClick && (now - this._lastLikeClick) < 1000) {
                        return;
                    }
                    this._lastLikeClick = now;


                    if (this.newComment.trim() === '' || this.isInteracting) {
                        return;
                    }

                    this.isInteracting = true;
                    const commentText = this.newComment.trim();
                    this.newComment = 'Mengirim...';

                    try {
                        let response = await fetch(`/post/detail/${slug}/comment`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                comment: commentText
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`Comment failed with status: ${response.status}`);
                        }

                        let newComment = await response.json();

                        this.comments.unshift(newComment);
                        this.newComment = '';

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Berhasil Mengirim Komentar!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });



                    } catch (error) {
                        this.newComment = commentText;
                    } finally {
                        setTimeout(() => {
                            this.isInteracting = false;
                            console.log('✅ Comment interaction completed');
                        }, 100);
                    }
                },

                scrollToComments() {
                    this.$refs.commentForm.scrollIntoView({ behavior: 'smooth' });
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const now = new Date();
                    const diffInSeconds = Math.floor((now - date) / 1000);

                    if (diffInSeconds < 60) return 'baru saja';
                    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit lalu`;
                    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam lalu`;

                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                },

                async shareContent() {
                    // Jika browser support native share
                    if (navigator.share) {
                        try {
                            await navigator.share({
                                title: 'Kunjungi postingan ini!',
                                text: 'Lihat postingan ini',
                                url: window.location.href,
                            });
                        } catch (error) {
                            // User cancelled share, no action needed
                        }
                    } else {
                        this.openShareModal();
                    }
                },

                async openShareModal() {
                    try {
                        let response = await fetch(`/post/${slug}/share`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        });

                        if (response.ok) {
                            let data = await response.json();
                            this.shareUrl = data.share_url;

                        } else {
                            this.shareUrl = window.location.href;
                        }
                    } catch (error) {
                        this.shareUrl = window.location.href;
                    }
                    this.showShareModal = true;
                },

                shareToPlatform(platform) {
                    const text = `Kunjungi postingan ini: ${this.shareUrl}`;
                    let url = '';

                    switch(platform) {
                        case 'whatsapp':
                            url = `https://wa.me/?text=${encodeURIComponent(text)}`;
                            break;
                    }

                    if (url) {
                        window.open(url, '_blank', 'width=600,height=400');
                        this.showShareModal = false;
                    }
                },

                async copyToClipboard() {
                    try {
                        await navigator.clipboard.writeText(this.shareUrl);
                        Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Link berhasil disalin!',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });
                    } catch (err) {
                        // Fallback
                        const textArea = document.createElement('textarea');
                        textArea.value = this.shareUrl;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                    }
                    this.showShareModal = false;
                },

            }
        }

        function postCarousel(mediaUrls) {
            return {
                mediaUrls: mediaUrls.filter(url => url),
                currentSlide: 0,
                isDragging: false,
                startX: 0,
                currentTranslate: 0,
                prevTranslate: 0,

                isLightboxOpen: false,
                lightboxUrl: null,
                lightboxType: null,

                init() {
                    if (this.mediaUrls.length === 0) {
                        this.mediaUrls = [null];
                    }
                    this.$watch('currentSlide', () => this.setSliderPosition(true));
                    this.setSliderPosition(false);
                    window.addEventListener('keydown', e => this.handleKeydown(e));
                },

                openLightbox(url, type = 'image') {
                    this.lightboxUrl = url;
                    this.lightboxType = type;
                    this.isLightboxOpen = true;
                    document.body.style.overflow = 'hidden'; // disable scroll
                },

                closeLightbox() {
                    this.isLightboxOpen = false;
                    this.lightboxUrl = null;
                    this.lightboxType = null;
                    document.body.style.overflow = '';
                },

                handleKeydown(event) {
                    if (event.key === 'Escape' && this.isLightboxOpen) {
                        this.closeLightbox();
                    }
                },

                setSliderPosition(animated = true) {
                    const slider = this.$refs.slider;
                    if (!slider) return;

                    slider.style.transition = animated ? 'transform 0.3s ease-out' : 'none';
                    const percentTranslate = this.currentSlide * -100;
                    slider.style.transform = `translateX(${percentTranslate}%)`;
                    this.currentTranslate = percentTranslate * (slider.offsetWidth / 100);
                    this.prevTranslate = this.currentTranslate;
                },

                startDrag(event) {
                    if (this.mediaUrls.length <= 1) return;
                    this.isDragging = true;
                    this.startX = event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
                    this.$refs.slider.style.transition = 'none';
                },

                drag(event) {
                    if (!this.isDragging) return;
                    const currentX = event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
                    const diff = currentX - this.startX;
                    this.$refs.slider.style.transform = `translateX(${this.prevTranslate + diff}px)`;
                },

                endDrag(event) {
                    if (!this.isDragging) return;
                    this.isDragging = false;
                    const slider = this.$refs.slider;
                    const currentX = event.type.includes('mouse') ? event.pageX : event.changedTouches[0].clientX;
                    const movedBy = currentX - this.startX;
                    const threshold = slider.offsetWidth / 4;

                    if (movedBy < -threshold && this.currentSlide < this.mediaUrls.length - 1) {
                        this.currentSlide++;
                    } else if (movedBy > threshold && this.currentSlide > 0) {
                        this.currentSlide--;
                    }

                    this.setSliderPosition();
                },

                handleResize() {
                    this.setSliderPosition(false);
                },
            }
        }


        </script>
    @endpush

        <main x-data="postInteractions('{{ $post->slug }}')" x-init="init()" class=" mb-16 md:mb-7 py-8 px-4 sm:px-4 mx-auto max-w-md bg-gradient-to-t from-slate-800 to-transparent rounded-b-xl ">

            @php

                $typeMap = [
                    '1' => 'feed',
                    '2' => 'carousel',
                    '3' => 'story',
                    '4' => 'reel',
                ];

                $type = $typeMap[$post->content_type] ?? 'feed';

                switch ($type) {
                    case 'feed':
                        $aspect_class = 'aspect-[4/5]';
                        $media_urls = [$post->getFirstMediaUrl('feed')];
                        $is_multi_media = false;
                        // dd( $is_multi_media);
                        break;
                    case 'carousel':
                        $aspect_class = 'aspect-[4/5]';
                        $media_urls = $post->getMedia('carousel')->map(fn($media) => $media->getFullUrl())->toArray();
                        $is_multi_media = count($media_urls) > 1;
                        break;
                    case 'story':
                        $aspect_class = 'aspect-[9/16]';
                        $media_urls = $post->getMedia('story')->map(fn($media) => $media->getFullUrl())->toArray();
                        $is_multi_media = count($media_urls) > 1;
                        break;
                    case 'reel':
                    default:
                        $aspect_class = 'aspect-[9/16]';
                        $media_urls = [$post->getFirstMediaUrl('reel')];
                        $is_multi_media = false;
                        break;
                }

                if (empty($media_urls) || (count($media_urls) === 1 && empty($media_urls[0]))) {
                    $media_urls = [null]; // Ensure at least one null if no media
                    $is_multi_media = false;
                }
                $username = $post->user->username ?? 'Pengguna Tidak Dikenal';
            @endphp

            <div class="mb-6 ">
                <div class="p-4 flex justify-between items-start">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gray-500 flex items-center justify-center text-white">
                            <img
                                src="{{ $post->user->image ? asset('storage/' . $post->user->image) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->fullname).'&background=0D8ABC&color=fff' }}"
                                class="w-full h-full rounded-full object-cover border-2 border-slate-700 shadow mx-auto sm:mx-0"
                                alt="{{ $post->user->fullname }}"
                            >
                        </div>
                        <div>
                            <span class="text-white font-semibold">{{ $username }}</span>
                            @if ($post->status === 'revision')
                                <span class="text-xs font-medium px-3 py-1 rounded-full text-white bg-rose-500  ml-2">
                                    Revision
                                </span>
                            @endif
                        </div>
                    </div>

                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        @if (Auth::id() === $post->user_id)
                            <button @click="open = !open" class="text-gray-400 hover:text-white focus:outline-none p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                            </button>

                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 rounded-lg shadow-2xl bg-gray-700 ring-1 ring-gray-600 z-60">
                                @role(['staff', 'superadmin'])
                                <form
                                    action="{{ route('post.delete', $post->slug) }}"
                                    x-ref="deleteForm"
                                    @submit.prevent method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-gray-600 rounded-t-lg w-full text-left"
                                            @click="
                                                Swal.fire({
                                                    title: 'Anda yakin?',
                                                    text: 'Apakah anda yakin ingin menghapus postingan ini?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3B82F6',
                                                    cancelButtonColor: '#EF4444',
                                                    confirmButtonText: 'Ya, Hapus!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $refs.deleteForm.submit();
                                                    }
                                                })
                                            ">
                                        Hapus
                                    </button>
                                </form>
                                    <a href="{{ route('post.edit', $post->slug) }}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Edit</a>
                                @endrole
                                @role('staff')
                                    <a href="{{ route('post.revision', $post->slug) }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 rounded-b-lg">
                                        Revisi
                                    </a>
                                @endrole
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Media Content -->

                <div
                    x-data="postCarousel({{ json_encode($media_urls) }})"
                    x-init="init"
                    @resize.window="handleResize()"
                    class="relative mx-4 mb-4 bg-slate-600 rounded-lg overflow-hidden {{ $aspect_class }}"
                >
                    <div
                        class="flex w-full h-full cursor-grab"
                        x-ref="slider"
                        @mousedown.prevent="startDrag"
                        @mousemove.prevent="drag"
                        @mouseup.prevent="endDrag"
                        @mouseleave.prevent="endDrag"
                        @touchstart.prevent="startDrag"
                        @touchmove.prevent="drag"
                        @touchend.prevent="endDrag"
                        @touchcancel.prevent="endDrag"
                        :class="{ 'cursor-grabbing': isDragging }"
                    >
                        @foreach ($media_urls as $url)
                            <div class="flex-none w-full h-full flex items-center justify-center">
                                @if ($url)
                                    @if (Str::endsWith($url, ['.mp4', '.mov', '.webm']))
                                        <video
                                            @click="openLightbox('{{ $url }}', 'video')"
                                            src="{{ $url }}"
                                            controls
                                            class="w-full h-full object-cover"
                                        ></video>
                                    @else
                                        <img
                                            @click="openLightbox('{{ $url }}', 'image')"
                                            src="{{ $url }}"
                                            alt="Slide {{ $loop->iteration }}"
                                            class="w-full h-full object-cover"
                                        >
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <template x-if="mediaUrls.length > 1">
                        <div class="absolute top-3 right-3 bg-black/60 text-white text-xs px-2 py-1 rounded-full z-10">
                            <span x-text="currentSlide + 1"></span>/<span x-text="mediaUrls.length"></span>
                        </div>
                    </template>

                    <div
                        x-show="isLightboxOpen"
                        x-transition.opacity
                        @click.self="closeLightbox"
                        class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center cursor-zoom-out"
                        x-cloak
                    >
                        <template x-if="lightboxType === 'image'">
                            <img :src="lightboxUrl" class="max-w-full max-h-full object-contain">
                        </template>
                        <template x-if="lightboxType === 'video'">
                            <video :src="lightboxUrl" controls autoplay class="max-w-full max-h-full object-contain"></video>
                        </template>
                    </div>
                </div>

                <div class="border border-slate-700 bg-slate-800 flex items-center justify-around py-2 px-5 mx-4 mb-4 rounded-xl">
                    {{-- Button Like --}}
                    <button @click.stop.prevent="toggleLike()"
                            :disabled="isInteracting"
                            :class="liked ? 'text-red-500 hover:text-red-400' : 'text-slate-400 hover:text-red-500'"
                            class="flex items-center p-2 rounded-lg transition duration-150 ease-in-out">
                        <span x-text="likeCount" class="mr-2 font-medium"></span>
                        <svg :fill="liked ? 'currentColor' : 'none'"
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        </svg>
                    </button>

                    <div class="w-px h-6 bg-slate-600"></div>

                    {{-- Button Comment --}}
                    <button @click="scrollToComments()"
                            class="flex items-center text-slate-400 hover:text-blue-500 p-2 rounded-lg">
                        <span x-text="comments.length" class="mr-2 font-medium"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </button>

                    <div class="w-px h-6 bg-slate-600"></div>

                    <button @click="shareContent()" class="flex items-center text-slate-400 hover:text-green-500 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                            <polyline points="16 6 12 2 8 6"/>
                            <line x1="12" y1="2" x2="12" y2="15"/>
                        </svg>
                    </button>


                    <!-- Share Modal -->
                    <template x-teleport="body">
                        <div x-show="showShareModal"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 backdrop-blur-sm p-4"
                            x-cloak>
                            <div @click.outside="showShareModal = false"
                                class="bg-slate-800 rounded-2xl shadow-xl w-full max-w-xs mx-auto">

                                <!-- Header -->
                                <div class="flex items-center justify-between p-4 border-b border-slate-700">
                                    <h3 class="text-lg font-semibold text-white">Bagikan</h3>
                                    <button @click="showShareModal = false" class="text-slate-400 hover:text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Share Options -->
                                <div class="p-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- WhatsApp -->
                                        <button @click="shareToPlatform('whatsapp')"
                                                class="flex flex-col items-center p-3 rounded-xl bg-green-500 hover:bg-green-600 transition duration-200 transform hover:scale-105">
                                            <svg class="w-6 h-6 text-white mb-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.485"/>
                                            </svg>
                                            <span class="text-xs text-white">WhatsApp</span>
                                        </button>

                                        <!-- Copy Link -->
                                        <button @click="copyToClipboard()"
                                                class="flex flex-col items-center p-3 rounded-xl bg-purple-500 hover:bg-purple-600 transition duration-200 transform hover:scale-105">
                                            <svg class="w-6 h-6 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-xs text-white">Salin</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="px-4 mt-4 text-sm text-white">
                    <template x-if="userLiked.length > 0">
                        <div class="flex items-center flex-wrap gap-1 cursor-pointer" @click="showLikesModal = true">
                            <span>Disukai oleh</span>

                            <!-- User pertama -->
                            <template x-if="userLiked[0]">
                                <div class="flex items-center gap-1">
                                    <img
                                        x-bind:src="userLiked[0].image
                                            ? '{{ asset('storage') }}/' + userLiked[0].image
                                            : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'"
                                        class="size-5 rounded-full bg-slate-800 object-cover"
                                        alt=""
                                    >
                                    <span class="font-semibold" x-text="userLiked[0].username || userLiked[0].fullname"></span>
                                </div>
                            </template>

                            <!-- Jika lebih dari 1 -->
                            <template x-if="userLiked.length > 1">
                                <span>
                                    dan <span x-text="userLiked.length - 1"></span> lainnya
                                </span>
                            </template>
                        </div>
                    </template>

                    <p x-show="userLiked.length === 0" class="text-gray-400 italic">Belum ada yang menyukai.</p>
                </div>


                <!-- Modal Daftar Like -->
                <div
                    x-show="showLikesModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click.outside="showLikesModal = false"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    x-cloak
                >
                    <div class="bg-slate-800 rounded-2xl shadow-xl w-full max-w-xs mx-auto">

                        <div class="flex items-center justify-between p-4 border-b border-slate-700">
                            <h3 class="text-lg font-semibold text-white">Disukai oleh</h3>
                            <button @click="showLikesModal = false" class="text-slate-400 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex flex-col p-4 gap-3 max-h-96 overflow-y-auto">
                            <template x-for="user in userLiked" :key="user.id">
                                <div class="flex items-center gap-3">
                                    <img
                                        x-bind:src="user.image
                                            ? '{{ asset('storage') }}/' + user.image
                                            : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'"
                                        class="size-10 rounded-full object-cover bg-slate-800"
                                        alt=""
                                    >
                                    <span class="text-white font-medium" x-text="user.username || user.fullname"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $revisions = $post->status === 'revision'
                    ? $post->revision   // kalau hasil revisi → ambil revisi lama (post_id)
                    : $post->oldRevision; // kalau post utama → ambil revisi baru (new_post_id)
            @endphp

            @if ($revisions->count())
                <div class="px-4 mt-2">
                    <h3 class="text-sm font-semibold text-cyan-300">
                        {{ $post->status === 'revision' ? 'Revisi Baru' : 'Revisi Lama' }}
                    </h3>

                    <ul class="mt-2 text-sm">
                        @foreach ($revisions as $rev)
                            <li class="text-gray-300">
                                <a
                                    href="{{ route('post.detail', $post->status === 'revision' ? $rev->newPost->slug : $rev->post->slug) }}"
                                    class="text-gray-300 hover:text-sky-400 transition-colors duration-150"
                                >
                                    Revisi #{{ $rev->rev_number }} oleh {{ $rev->user->username }}
                                    <span class="text-gray-500">
                                        ({{ $rev->rev_at->diffForHumans() }})
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif




            @if ($post->title)
                <div class="px-4 mt-4 text-lg font-semibold text-gray-100 mb-4">
                    <h5 class="text-white">{{ $post->title }}</h5>
                </div>
            @endif
            @if ($post->caption)
                <div class="px-4 mb-4 flex flex-col gap-2">
                    <span class="text-sm font-semibold text-cyan-300">Caption</span>
                    <p class="text-sm text-white ">{{ $post->caption }}</p>
                </div>
            @endif
            @if ($post->hashtag || $tags)
                <div class="px-4 mb-4 flex flex-col gap-2">
                    <span class="text-sm font-semibold text-cyan-300">Tags</span>
                    <div class="flex flex-wrap justify-start gap-1 py-2">
                        @foreach($tags ?? [] as $tag)
                            <span class="px-2 py-1.5 text-sm font-normal bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white rounded-2xl">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                        @foreach($post->hashtag ?? [] as $tag)
                            <span class="px-2 py-1.5 text-sm font-normal bg-gradient-to-b from-cyan-500 to-sky-600 hover:from-sky-600 hover:to-blue-600 text-white rounded-2xl">
                                {{$tag}}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Section Komentar --}}
            <div class="mt-6 space-y-4">
                <div class="px-4 flex flex-col gap-2">
                    <span class="text-sm font-semibold text-cyan-300">Comments</span>
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="flex gap-3 border border-slate-700 bg-slate-800 p-4 rounded-xl">
                            <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white text-xs">
                                <span x-text="(comment.user.username || comment.user.fullname).charAt(0).toUpperCase()"></span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-100" x-text="comment.user.username || comment.user.fullname"></p>
                                <p class="text-gray-300 text-sm mt-1" x-text="comment.comment"></p>
                                <p class="text-gray-500 text-xs mt-1" x-text="formatDate(comment.created_at)"></p>
                            </div>
                        </div>
                    </template>
                    <p x-show="comments.length === 0" class="text-center text-gray-400 italic py-4">Belum ada komentar.</p>
                </div>
            </div>

            @role('superadmin')
            <div class="space-y-4">
                <div class="px-4 flex flex-col gap-2">
                    <div x-ref="commentForm" class="mt-6 flex gap-3">
                        <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white text-sm">
                            {{ substr(Auth::user()->fullname ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <textarea x-model="newComment"
                                    placeholder="Tambahkan komentar..."
                                    @keydown.enter.prevent="submitComment()" {{-- PERBAIKAN: prevent default --}}
                                    :disabled="isInteracting"
                                    class="w-full border border-slate-700 bg-slate-800 text-white rounded-xl p-3 focus:ring-1 focus:ring-green-500 focus:outline-none resize-none disabled:opacity-50"
                                    rows="6"></textarea>
                            <button @click.stop.prevent="submitComment()"
                                    :disabled="isInteracting || newComment.trim() === ''"
                                    class="mt-2 px-5 text-md py-2.5 bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white disabled:bg-gray-600 disabled:cursor-not-allowed rounded-lg text-white font-normal transition duration-150">
                                <span x-show="isInteracting">Mengirim...</span>
                                <span x-show="!isInteracting">Kirim</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endrole
        </main>
    </div>
</x-layouts.main>
