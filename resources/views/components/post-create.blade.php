@props(['postType' => [], 'status' => []])

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center w-full lg:w-3/4 mx-auto ">
    <div x-data="{
        feeds: 'Default',
        title: '',
        caption: '',
        imageFile: null,
        imagePreview: null, 
        templateTags: ['#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite', '#jasawesite'],
        optionalTags: [],
        maxOptionalTags: 4,
        newOptionalTag: '',
        
        // Gabungan semua hashtag untuk dikirim
        get allTags() {
            // Gabungkan templateTags dan optionalTags. Pastikan templateTags tidak memiliki duplikat
            let uniqueTemplateTags = [...new Set(this.templateTags)];
            return [...uniqueTemplateTags, ...this.optionalTags];
        },

        // Fungsi untuk menghapus optional tag
        removeOptionalTag(index) {
            this.optionalTags.splice(index, 1);
        },
        
        // Fungsi untuk menambahkan optional tag
        addOptionalTag() {
            if (this.newOptionalTag.trim() !== '' && this.optionalTags.length < this.maxOptionalTags) {
                // Pastikan tag dimulai dengan '#' dan di-lowercase
                let tag = this.newOptionalTag.trim().toLowerCase();
                if (!tag.startsWith('#')) {
                    tag = '#' + tag;
                }
                
                if (!this.optionalTags.includes(tag)) {
                    this.optionalTags.push(tag);
                    this.newOptionalTag = ''; // Reset input
                }
            }
        },
        
          // ✅ Preview upload image/video
        handleImageUpload(event) {
            const file = event.target.files[0];
            this.imageFile = file;
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreview = null;
            }
        }
    }" class="max-w-2xl mx-auto rounded-lg p-2 space-y-6 ">

        {{-- FORM START --}}
        {{-- Pastikan action diarahkan ke route store Anda dan menggunakan method POST. 
             Tambahkan enctype="multipart/form-data" karena ada file upload. --}}
        <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                <div class="flex gap-3 items-center">
                    <div class="space-y-2">
                        <label for="content_type_display" class="block text-sm font-semibold text-white">Content Type</label>
                        <div 
                            x-data="{ 
                                open: false, 
                                selected: '{{ array_values($postType)[0] ?? 'Select type' }}' 
                            }" 
                            @click.away="open = false" 
                            class="relative w-full"
                        >
                            <button 
                                @click="open = !open" 
                                class="flex items-center justify-between w-full space-x-2 px-4 py-3 rounded-lg text-sm font-medium text-gray-100 border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 transition duration-150 focus:outline-none focus:ring-2 focus:ring-sky-400"
                            >
                                <span x-text="selected"></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div 
                                x-show="open" 
                                x-transition 
                                class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-slate-800 ring-1 ring-black ring-opacity-5 focus:outline-none origin-top-left"
                            >
                                <div class="py-1" role="menu">
                                    @foreach ($postType as $key => $label)
                                        <a href="#" 
                                        {{-- $refs.typeInput.value akan mengisi hidden input content_type --}}
                                        @click.prevent="selected = '{{ $label }}'; open = false; $refs.typeInput.value = '{{ $key }}'"
                                        class="text-gray-100 block px-4 py-2 text-sm hover:bg-gray-600" 
                                        role="menuitem">{{ $label }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Input Tersembunyi untuk Content Type --}}
                            <input type="hidden" name="content_type" x-ref="typeInput" value="{{ array_key_first($postType) }}">
                        </div>
                    </div>

                    <p class="text-sm italic text-gray-500 dark:text-gray-400">
                        *Choose type of post content
                    </p>
                </div>

                <button type="submit" class="px-5 py-2.5 bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white font-semibold rounded-lg focus:outline-none transition duration-150">
                    Done
                </button>
            </div>

            <div class="flex w-full flex-col lg:flex-row gap-3">
                <div class="space-y-2 flex-1">
                    <label for="title" class="block text-sm font-semibold text-white">Title</label>
                    {{-- Tambahkan name="title" --}}
                    <input type="text" id="title" name="title" x-model="title" placeholder="" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150">
                </div>
            </div>

            <div class="flex w-full flex-row gap-3">
                <div class="space-y-2 w-1/2">
                    <label for="status_display" class="block text-sm font-semibold text-white">Status</label>
                    <div 
                        x-data="{ 
                            open: false, 
                            selected: '{{ array_values($status)[0] ?? 'Select status' }}' 
                        }" 
                        @click.away="open = false" 
                        class="relative w-full"
                    >
                        <button 
                            @click="open = !open" 
                            class="flex items-center justify-between w-full space-x-2 px-4 py-3 rounded-lg text-sm font-medium text-gray-100 border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 transition duration-150 focus:outline-none focus:ring-2 focus:ring-sky-400"
                        >
                            <span x-text="selected"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div 
                            x-show="open" 
                            x-transition 
                            class="absolute z-10 mt-2 w-full rounded-md shadow-lg bg-slate-800 ring-1 ring-black ring-opacity-5 focus:outline-none origin-top-right right-0"
                        >
                            <div class="py-1" role="menu">
                                @foreach ($status as $key => $label)
                                    <a href="#" 
                                    {{-- $refs.statusInput.value akan mengisi hidden input status --}}
                                    @click.prevent="selected = '{{ $label }}'; open = false; $refs.statusInput.value = '{{ $key }}'"
                                    class="text-gray-100 block px-4 py-2 text-sm hover:bg-gray-600" 
                                    role="menuitem">{{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        {{-- Input Tersembunyi untuk Status --}}
                        <input type="hidden" name="status" x-ref="statusInput" value="{{ array_key_first($status) }}">
                    </div>
                </div>


                <div class="space-y-2 w-1/2">
                    <label for="post_at" class="block text-sm font-semibold text-white">Tanggal</label>
                    {{-- Tambahkan name="post_at" --}}
                    <input type="date" id="post_at" name="post_at" x-model="date" placeholder="" class="w-full px-4 py-2.5 rounded-lg fill-white border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150">
                </div>
            </div>

            <div class="space-y-2">
                <label for="image_upload" class="block text-sm font-semibold text-white">Image Feeds</label>

                <label for="image_upload"
                    @class([
                        'flex flex-col items-center justify-center',
                        'aspect-[4/5]' => $postType == 'feed' || $postType == 'corolusel',
                        'aspect-[9/16]' => !($postType == 'feed' || $postType == 'corolusel'),
                        'border-2 border-dashed border-gray-500 rounded-lg cursor-pointer bg-slate-800 hover:bg-slate-800/70 transition duration-150 relative overflow-hidden'
                    ])>
                    <template x-if="imagePreview">
                        <div class="absolute inset-0">
                            <!-- ✅ Preview gambar atau video -->
                            <template x-if="imageFile && imageFile.type.startsWith('image/')">
                                <img :src="imagePreview" alt="Preview" class="object-cover w-full h-full rounded-lg" />
                            </template>
                            <template x-if="imageFile && imageFile.type.startsWith('video/')">
                                <video :src="imagePreview" controls class="object-cover w-full h-full rounded-lg"></video>
                            </template>
                        </div>
                    </template>

                    <!-- Ikon upload muncul kalau belum ada preview -->
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!imagePreview">
                        <div class="bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white p-1 rounded-full">
                            <svg class="w-6 h-6 lg:w-10 lg:h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" x-text="imageFile ? imageFile.name : 'Add Image File'"></p>
                    </div>

                    <input id="image_upload" type="file" name="media[]" class="hidden" @change="handleImageUpload($event)" accept="image/*,video/mp4">
                </label>

                @error('media.*')
                    <p class="text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>


            <div class="space-y-2">
                <label for="caption" class="block text-sm font-semibold text-white">Caption</label>
                {{-- Tambahkan name="caption" --}}
                <textarea id="caption" name="caption" x-model="caption" rows="8" class="w-full px-4 py-3 rounded-lg border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150"></textarea>
            </div>

            {{-- Input Tersembunyi untuk Hashtag --}}
            {{-- Karena Anda perlu mengirim array hashtag, kita gunakan input tersembunyi.
                 Larave/PHP akan mengenali input dengan name="hashtag[]" sebagai array. --}}
            <template x-for="tag in allTags">
                <input type="hidden" name="hashtag[]" :value="tag">
            </template>

            <div class="space-y-3">
                <h3 class="block text-xl font-semibold text-gray-900 dark:text-white">Template Tags</h3>
                <div class="flex flex-wrap justify-center gap-2 p-2 border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 rounded-lg ">
                    <template x-for="(tag, index) in templateTags" :key="index">
                        <span x-text="tag" class="px-2.5 py-1.5 text-sm font-normal bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white rounded-2xl">
                        </span>
                    </template>
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="block text-sm font-semibold text-white">Optional Tags <span class="text-sm italic text-gray-500 dark:text-gray-400">*Max added <span x-text="maxOptionalTags"></span> tags</span></h3>
                
                <div class="flex flex-wrap gap-2">
                    <template x-for="(tag, index) in optionalTags" :key="index">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-normal bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white rounded-2xl">
                            <span x-text="tag"></span>
                            <button @click="removeOptionalTag(index)" type="button" class="ml-1.5 -mr-0.5 inline-flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full text-red-400">
                                <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                    <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"></path>
                                </svg>
                            </button>
                        </span>
                    </template>
                </div>

                <div class="flex items-center space-x-2">
                    <input 
                        type="text" 
                        x-model="newOptionalTag" 
                        @keydown.enter.prevent="addOptionalTag()" 
                        :disabled="optionalTags.length >= maxOptionalTags"
                        placeholder="Tambah tag baru (Tekan Enter)" 
                        class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg bg-slate-800 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 outline-0 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                    <button 
                        @click="addOptionalTag()"
                        :disabled="optionalTags.length >= maxOptionalTags"
                        type="button" {{-- Ubah dari default type="submit" menjadi type="button" agar tidak mensubmit form --}}
                        class="p-2 text-white rounded-full shadow-md bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition duration-150 disabled:bg-gray-400 dark:disabled:bg-gray-600"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <p x-show="optionalTags.length >= maxOptionalTags" class="text-sm text-red-500 dark:text-red-400">Batas maksimum <span x-text="maxOptionalTags"></span> tag telah tercapai.</p>
            </div>
        </form>
        {{-- FORM END --}}

    </div>
</div>