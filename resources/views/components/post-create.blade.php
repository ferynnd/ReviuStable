@props(['postType' => [], 'status' => [] , 'type' => [] , 'tags' => [] ])

<div class="flex flex-col items-center justify-center">
        <div class="flex  w-full justify-between items-center pb-6 border-b border-gray-700">
            <div class="flex gap-3 items-center">
                <div class="space-y-2">
                    <div class="relative">
                        <select
                            onchange="window.location='?type='+this.value"
                            name="type"
                            class="appearance-none w-full px-5 py-3 rounded-lg border-2 border-gray-500 bg-slate-800 text-gray-100 focus:ring-sky-400 focus:border-sky-400 pr-10 leading-tight"
                        >
                            @foreach ($postType as $key => $label)
                                  <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>
                                      {{ $label }}
                                  </option>
                            @endforeach
                        </select>

                        <svg class="w-5 h-5 text-gray-300 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm italic text-gray-500 dark:text-gray-400">
                    *Pilih tipe konten postingan
                </p>
            </div>
        </div>


        @if($type === 'feed')
            <x-form.feed :status="$status" :tags="$tags"/>
        @elseif($type === 'carousel')
            <x-form.carousel :status="$status" :tags="$tags"/>
        @elseif($type === 'story')
            <x-form.story :status="$status" :tags="$tags"/>
        @elseif($type === 'reel')
            <x-form.reel :status="$status" :tags="$tags"/>
        @endif
</div>
