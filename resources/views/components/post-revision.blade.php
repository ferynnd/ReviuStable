@props(['postType' => [], 'status' => [] , 'type' => [], 'oldPost' ])

@php
    // Mapping angka ke string type
    $typeMap = [
        1 => 'feed',
        2 => 'carousel',
        3 => 'story',
        4 => 'reel',
    ];

    $currentType = $typeMap[$oldPost->content_type] ?? $type;
@endphp


<div class="flex flex-col items-center justify-center">
        <div class="flex  w-full justify-between items-center pb-6 border-b border-gray-700">
            <div class="flex gap-3 items-center">
                <div class="space-y-2">
                    <div class="relative">
                        <select
                            name="type"
                            class="appearance-none w-full px-5 py-3 rounded-lg border-2 border-gray-500 bg-slate-800 text-gray-100 focus:ring-sky-400 focus:border-sky-400 pr-10 leading-tight"
                        >
                            @foreach ($postType as $key => $label)
                                <option value="{{ $key }}" {{ $currentType === $key ? 'selected' : '' }}>
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


        @if($currentType === 'feed')
            <x-form.revision.feed :status="$status" :oldPost="$oldPost"/>
        @elseif($currentType === 'carousel')
            <x-form.revision.carousel :status="$status" :oldPost="$oldPost"/>
        @elseif($currentType === 'story')
            <x-form.revision.story :status="$status" :oldPost="$oldPost"/>
        @elseif($currentType === 'reel')
            <x-form.revision.reel :status="$status" :oldPost="$oldPost"/>
        @endif
</div>
