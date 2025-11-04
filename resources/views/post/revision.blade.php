<x-layouts.main>

    <div class="max-w-2xl mx-auto">

         <header class="h-20 flex items-center gap-7 px-6 border-b border-slate-700 ">
            <a onclick="history.back()" class="p-2 rounded-lg hover:bg-cyan-400/80 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
            </a>
            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-minus-vertical text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /></svg></span>
            <div class="text-lg font-semibold text-cyan-400">Revisi Content</div>
        </header>

         <main class="py-8 px-4 sm:px-4 mx-auto max-w-md mb-14">
             <x-post-revision :post-type="$postType" :status="$status" :type="$type" :oldPost="$oldPost"  :tags="$tags" />
        </main>


    </div>

</x-layouts.main>
