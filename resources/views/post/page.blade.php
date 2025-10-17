<x-layouts.main>

    <div class="max-w-2xl mx-auto">

        <header class="h-20 flex items-center justify-between px-6 border-b border-gray-700 ">
            <div class="text-lg font-semibold text-white">Post Content</div>
        </header>

         <main class="pt-4 md:pb-8 pb-16 lg:pt-8 lg:pb-8 px-2 sm:px-0">
            <x-post-create :post-type="$postType" :status="$status"/>
        </main>


    </div>

</x-layouts.main>