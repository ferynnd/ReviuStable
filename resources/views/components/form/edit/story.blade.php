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
        selectedStatus: @js($oldpost->status),
        postAt: @js($oldpost->post_at ? $oldpost->post_at->format('Y-m-d') : date('Y-m-d')),
        oldPreviews: @js($oldpost->getMedia($oldpost->getMediaCollectionName($oldpost->content_type))->map(fn($m) => $m->getUrl())),
    })"

>
    @csrf
    @method('PUT')

    <input type="hidden" name="content_type" value="3">

        <div class="flex w-full flex-col lg:flex-row gap-3">
            <div class="space-y-2 flex-1">
                <label for="title" class="block text-sm font-semibold text-white">Title</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    x-model="title"
                    placeholder="Judul postingan.."
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
                <div class="relative">
                    <input
                        type="date"
                        id="post_at"
                        name="post_at"
                        x-model="postAt" {{-- Sinkronisasi tanggal dengan nilai awal dari prop --}}
                        placeholder=""
                        class="w-full px-4 py-2.5 rounded-lg fill-white border-2 border-gray-500 bg-slate-800 hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400 focus:ring-sky-400 focus:border-sky-400 transition duration-150 calendar-input"
                    >

                    <!-- Icon custom -->
                    <svg class="w-5 h-5 text-white absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-white">Image Story
                    <span class="text-xs text-gray-400 mt-1 italic">
                        *Gambar lama akan tetap digunakan jika tidak mengganti file baru.
                    </span>
                </label>

                    <div class="relative w-full aspect-[9/16] bg-slate-800 border-2 border-dashed border-gray-500 rounded-xl overflow-hidden">

                        <template x-if="activePreview">
                            <img
                                :src="activePreview"
                                alt="Preview Utama"
                                class="absolute inset-0 w-full h-full object-contain transition duration-300"
                                id="mainImage"
                            >
                        </template>

                        <div
                            x-show="!activePreview"
                            class="absolute inset-0 flex items-center justify-center text-gray-300 text-sm"
                        >
                            Belum ada gambar
                        </div>

                        <button
                            type="button"
                            @click="prev()"
                            x-show="previews.length > 1"
                            id="prev"
                            class="absolute left-3 top-1/2 -translate-y-1/2 bg-gray-700/70 hover:bg-gray-600 text-white p-2 rounded-full z-20 transition duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2a10 10 0 0 1 .324 19.995l-.324 .005l-.324 -.005a10 10 0 0 1 .324 -19.995zm.707 5.293a1 1 0 0 0 -1.414 0l-4 4a1.048 1.048 0 0 0 -.083 .094l-.064 .092l-.052 .098l-.044 .11l-.03 .112l-.017 .126l-.003 .075l.004 .09l.007 .058l.025 .118l.035 .105l.054 .113l.043 .07l.071 .095l.054 .058l4 4l.094 .083a1 1 0 0 0 1.32 -1.497l-2.292 -2.293h5.585l.117 -.007a1 1 0 0 0 -.117 -1.993h-5.586l2.293 -2.293l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                        </button>

                        <button
                            type="button"
                            @click="next()"
                            x-show="previews.length > 1"
                            id="next"
                            class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-700/70 hover:bg-gray-600 text-white p-2 rounded-full z-20 transition duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-arrow-right"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.324 .005a10 10 0 1 1 -.648 0l.324 -.005zm.613 5.21a1 1 0 0 0 -1.32 1.497l2.291 2.293h-5.584l-.117 .007a1 1 0 0 0 .117 1.993h5.584l-2.291 2.293l-.083 .094a1 1 0 0 0 1.497 1.32l4 -4l.073 -.082l.064 -.089l.062 -.113l.044 -.11l.03 -.112l.017 -.126l.003 -.075l-.007 -.118l-.029 -.148l-.035 -.105l-.054 -.113l-.071 -.111a1.008 1.008 0 0 0 -.097 -.112l-4 -4z" /></svg>
                        </button>

                        <button
                            type="button"
                            @click="removeImage(current)"
                            x-show="previews.length > 0"
                            class="absolute top-2 right-2 bg-red-600/80 hover:bg-red-700 text-white p-1 rounded-full z-20 transition duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 3a1 1 0 0 0-1 1v1H5v2h14V5h-3V4a1 1 0 0 0-1-1H9zM7 9v10a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9H7z"/>
                            </svg>
                        </button>

                        <div
                            x-show="previews.length > 0"
                            class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-black/50 text-white px-3 py-1 rounded-full text-xs"
                        >
                            <span x-text="current + 1"></span>/<span x-text="previews.length"></span>
                        </div>
                    </div>


                    {{-- === LIST THUMBNAIL === --}}
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(thumb, i) in previews" :key="i">
                            <img
                                :src="thumb"
                                @click="setActive(i)"
                                :class="{'ring-2 ring-purple-500 border-purple-500': current === i, 'border-gray-600': current !== i}"
                                class="thumb w-24 h-32 object-cover rounded-lg border-2 cursor-pointer transition duration-150"
                            >
                        </template>

                        <template x-for="url in oldPreviews" :key="url">
                            <input type="hidden" name="old_previews[]" :value="url">
                        </template>

                        <label class="flex flex-col items-center justify-center w-24 h-32 border-2 border-dashed border-gray-500 rounded-lg cursor-pointer bg-slate-800 hover:bg-slate-700 transition duration-150">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <input
                                type="file"
                                x-ref="fileInput"
                                class="hidden"
                                name="media[]"
                                multiple
                                accept="image/*"
                                @change="handleImageUpload($event)"
                            >
                        </label>
                    </div>

                @error('media.*')
                    <p class="text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
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
            selectedStatus: initial.selectedStatus || 'draft',
            postAt: initial.postAt || '{{ date("Y-m-d") }}',

            files: [],
            oldPreviews: initial.oldPreviews || [],
            previews: initial.oldPreviews ? [...initial.oldPreviews] : [],
            current: 0,

            get activePreview() {
                return this.previews.length > 0 ? this.previews[this.current] : null;
            },

            handleImageUpload(e) {
                const newFiles = Array.from(e.target.files);
                if (!newFiles.length) return;

                const readers = newFiles.map(file => {
                    this.files.push(file);
                    return new Promise(resolve => {
                        const reader = new FileReader();
                        reader.onload = ev => resolve(ev.target.result);
                        reader.readAsDataURL(file);
                    });
                });

                Promise.all(readers).then(results => {
                    this.previews.push(...results);

                    if (this.previews.length === results.length) this.current = 0;
                });
            },

            setActive(i) {
                if (this.previews[i]) this.current = i;
            },

            next() {
                if (this.previews.length > 0)
                    this.current = (this.current + 1) % this.previews.length;
            },

            prev() {
                if (this.previews.length > 0)
                    this.current = (this.current - 1 + this.previews.length) % this.previews.length;
            },

            removeImage(i) {
                if (i < 0 || i >= this.previews.length) return;

                const removed = this.previews[i];
                this.oldPreviews = this.oldPreviews.filter(url => url !== removed);

                this.files.splice(i, 1);
                this.previews.splice(i, 1);
                if (this.current >= this.previews.length)
                this.current = Math.max(0, this.current  - 1);
            }
        }
    }
</script>
@endpush
