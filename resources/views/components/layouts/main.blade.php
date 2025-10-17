<x-layouts.app>
    <div class="container mx-auto h-screen flex">
        <aside class=" w-auto md:w-2/5 lg:w-1/3  max-h-screen hidden md:block">
            <x-sidebar></x-sidebar>
        </aside>
        <main class="   flex-1 h-screen overflow-y-auto">{{$slot}}</main>
        <x-bottom-nav></x-bottom-nav>
    </div>
</x-layouts.app>