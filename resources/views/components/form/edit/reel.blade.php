@props([
    'oldpost',
    'status' => [],
    'selectedStatus' => 1,
    'postAt' => date('Y-m-d'),
    'tags' => []
])

<form
    action="{{ route('post.update', $oldpost->slug) }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6 w-full"
    x-data="postForm({
        title: @js($oldpost->title),
        caption: @js($oldpost->caption),
        selectedStatus: @js($oldpost->status),
        postAt: @js($oldpost->post_at ? $oldpost->post_at->format('Y-m-d') : date('Y-m-d')),
        optionalTags: @js($oldpost->hashtag),
        oldPreviews: @js($oldpost->getMedia($oldpost->getMediaCollectionName($oldpost->content_type))->map(fn($m) => $m->getUrl())),
    })"

>
    @csrf
    @method('PUT')

    <input type="hidden" name="content_type" value="{{ $oldpost->content_type }}">

    <div class="flex w-full flex-col lg:flex-row gap-3">
        <div class="space-y-2 flex-1">
            <label for="title" class="block text-sm font-semibold text-white">Title</label>
            <input
                type="text"
                id="title"
                name="title"
                x-model="title"
                placeholder="Judul postingan..  "
                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150"
            >
        </div>
    </div>

    <div class="flex w-full flex-row gap-3">
        <div class="w-1/2 space-y-2">
            <label for="status" class="block text-sm font-semibold text-white">Status</label>
            <div class="relative">
                <select
                    id="status"
                    name="status"
                    x-model.number="selectedStatus"
                    class="appearance-none w-full px-5 py-3 rounded-lg border-2 border-gray-500 bg-slate-800 text-gray-100 focus:ring-sky-400 focus:border-sky-400 pr-10 leading-tight"
                >
                    @foreach ($status as $key => $label)
                        <option value="{{ $key }}" {{ $key == $selectedStatus ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <svg class="w-5 h-5 text-gray-300 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div class="space-y-2 w-1/2">
            <label for="post_at" class="block text-sm font-semibold text-white">Date</label>
            <input
                type="date"
                id="post_at"
                name="post_at"
                x-model="postAt"
                placeholder=""
                class="w-full px-4 py-2.5 rounded-lg fill-white border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150"
            >
        </div>
    </div>


<div class="space-y-2">
        <label class="block text-sm font-semibold text-white">Vidio Reel</label>

        <div class="relative w-full aspect-[9/16] bg-slate-800 border-2 border-dashed border-gray-500 rounded-xl overflow-hidden flex items-center justify-center">
            <input
                type="file"
                name="media"
                x-ref="fileInput"
                @change="handleVideoUpload($event)"
                accept="video/*"
                class="absolute inset-0 opacity-0 cursor-pointer"
                style="z-index: 15;" >

            <template x-if="previews.length === 0">
                <label
                    @click="$refs.fileInput.click()"
                    class="flex flex-col items-center justify-center w-full h-full cursor-pointer hover:bg-slate-700 transition"
                    style="z-index: 10;"
                >
                    <div class="bg-gradient-to-b from-purple-500 to-violet-600 text-white p-2 rounded-full">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="text-white text-sm mt-2">Upload Image</span>
                </label>
            </template>

            <template x-if="previews.length > 0">
                <div class="relative w-full h-full">
                    <video
                        :src="previews[0]"
                        controls
                        playsinline
                        class="absolute inset-0 w-full h-full object-cover hover:grayscale-50" ></video>

                    <input type="hidden" name="old_preview" :value="previews[0]">

                    <button
                        type="button"
                        @click.prevent="removeVideo()"class="absolute top-2 right-2 bg-red-600/80 hover:bg-red-700 text-white p-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trash size-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" />
                            <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        @error('media')
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>



    <div class="space-y-2">
        <label for="caption" class="block text-sm font-semibold text-white">Caption</label>
        <textarea
            id="caption"
            name="caption"
            x-model="caption"
            rows="8"
            placeholder="Tulis caption kamu..."
            class="w-full px-4 py-3 rounded-lg border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150"
        ></textarea>
    </div>

    <div class="space-y-3">
        @php
            $tag = $tags->pluck('name');
        @endphp

        <h3 class="block text-sm font-semibold text-white">Template Tags</h3>
        <div x-data="{templateTags: @js($tag) }" class="flex flex-wrap justify-center gap-2 p-2 border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 rounded-lg">
            <template x-for="(tag, index) in templateTags" :key="index">
                <span
                    x-text="tag"
                    class="px-2.5 py-1.5 text-sm font-normal bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white rounded-2xl">
                </span>
            </template>
        </div>
    </div>

    <div class="space-y-3">
        <h3 class="block text-sm font-semibold text-white">
            Optional Tags
            <span class="text-sm italic text-gray-500 dark:text-gray-400">*Max added <span x-text="maxOptionalTags"></span> tags</span>
        </h3>

            <div class="flex flex-wrap gap-2">
                <template x-for="(tag, index) in optionalTags" :key="tag">
                    <span class="inline-flex items-center px-3 py-1 text-sm font-normal bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700 text-white rounded-2xl">
                        <span x-text="tag"></span>
                        <button
                            @click="removeOptionalTag(index)"
                            type="button"
                            class="ml-1.5 -mr-0.5 inline-flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full text-red-400"
                        >
                            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"></path>
                            </svg>
                        </button>
                        <input type="hidden" name="hashtag[]" :value="tag">
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
                    type="button"
                    class="p-2 text-white rounded-full shadow-md bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition duration-150 disabled:bg-gray-400 dark:disabled:bg-gray-600"
                >
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <p x-show="optionalTags.length >= maxOptionalTags" class="text-sm text-red-500 dark:text-red-400">
                Batas maksimum <span x-text="maxOptionalTags"></span> tag telah tercapai.
            </p>
    </div>


        <button
            type="submit"
            class="px-5 w-full py-2.5 bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white font-semibold rounded-lg focus:outline-none transition duration-150">
            Selesai
        </button>
</form>


@push("js")
<script>
window.postForm = function(initial = {}) {
    return {
        // Inisialisasi dari prop atau nilai default
        title: initial.title || '',
        caption: initial.caption || '',
        selectedStatus: initial.selectedStatus || 'draft',
        postAt: initial.postAt || '{{ date("Y-m-d") }}',
        optionalTags: initial.optionalTags || [],


        files: [],
        previews: initial.oldPreviews || [],
        maxOptionalTags: 4,
        newOptionalTag: '',
        isAddingTag: false,

        get allTags() {
            return [...new Set(this.optionalTags)];
        },

        removeOptionalTag(index) {
            this.optionalTags.splice(index, 1);
        },

        addOptionalTag() {
            if (this.isAddingTag) return; // cegah double trigger
            this.isAddingTag = true;
            setTimeout(() => this.isAddingTag = false, 400); // cooldown 0.4 detik

            if (this.newOptionalTag.trim() !== '' && this.optionalTags.length < this.maxOptionalTags) {
                let tag = this.newOptionalTag.trim().replace(/[^a-z0-9]/gi, '').toLowerCase();
                if(tag === '') {
                    this.newOptionalTag = '';
                    return;
                }
                tag = '#' + tag;

                if (!this.optionalTags.includes(tag)) {
                    this.optionalTags.push(tag);
                    this.newOptionalTag = '';
                }
            }
        },

        handleVideoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('video/')) {
                alert('Hanya file video yang diperbolehkan.');
                event.target.value = '';
                return;
            }

            if (file.size > 20 * 1024 * 1024) {
                alert('Ukuran file maksimal 20MB.');
                event.target.value = '';
                return;
            }

            // Simpan file dan buat preview
            this.files = [file]; // untuk dikirim ke backend
            this.previews = [URL.createObjectURL(file)];
        },


        // Remove video untuk Reel
        removeVideo() {
            this.files = [];
            this.previews = [];
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

    }
}
</script>
@endpush
