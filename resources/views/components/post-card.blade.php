@props(['type' => 'feed'])

@php
if ($type === 'story') {
    $aspect_class = 'aspect-[9/16]'; // story portrait fullscreen
    $type_badge_color = 'bg-red-500';
} else {
    $aspect_class = 'aspect-[4/5]'; // feed 4:5 portrait
    $type_badge_color = 'bg-blue-500';
}
@endphp

<!-- Alpine.js untuk fungsionalitas Dropdown -->
{{-- Anda perlu memastikan AlpineJS dimuat di layout utama --}}
<div class="mb-6 ">
    <div class="p-4 flex justify-between items-start">
        <div class="flex items-center space-x-3">
            <!-- Avatar -->
            <div class="w-10 h-10 rounded-full bg-gray-500 flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            
            <div>
                <span class="text-white font-semibold">Nama Pengguna Dummy</span>
                <span class="text-xs font-medium px-3 py-1 rounded-full 
                      {{ $type_badge_color }} 
                      text-white ml-2">{{ ucfirst($type) }}</span>
            </div>
        </div>

        <!-- Tombol Dropdown (Dummy Alpine) -->
        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="text-gray-400 hover:text-white focus:outline-none p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
            </button>
            
            <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 rounded-lg shadow-2xl bg-gray-700 ring-1 ring-gray-600 z-10">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-gray-600 rounded-t-lg">Hapus (Delete)</a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Edit</a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 rounded-b-lg">Revisi</a>
            </div>
        </div>
    </div>

    <!-- Area Konten Media -->
    <div class="mx-4 mb-4 bg-slate-600 rounded-lg overflow-hidden flex items-center justify-center {{ $aspect_class }}">
        <p class="text-slate-200 text-lg font-bold">KONTEN MEDIA DUMMY ({{ strtoupper($type) }})</p>
    </div>

    <!-- Footer Aksi (Like, Comment, Share) -->
    <div class="border border-slate-700 bg-slate-800 flex items-center justify-around py-2 px-5 mx-4 mb-4 rounded-xl">
        <!-- Like -->
        <button class="flex items-center text-slate-400 hover:text-red-500 p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        </button>
        
        <div class="w-px h-6 bg-slate-600"></div>

        <!-- Comment -->
        <button class="flex items-center text-slate-400 hover:text-blue-500 p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </button>
        
        <div class="w-px h-6 bg-slate-600"></div>

        <!-- Share -->
        <button class="flex items-center text-slate-400 hover:text-green-500 p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
        </button>
    </div>
</div>
