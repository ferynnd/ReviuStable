<x-layouts.main>

    <div class="max-w-2xl mx-auto">

        <header
            class="sticky top-0 z-50 h-20 flex items-center justify-between px-6 border-b border-slate-700
                bg-slate-900/90 backdrop-blur supports-[backdrop-filter]:bg-slate-900/70">
            <div class="text-lg font-semibold text-cyan-400">Calendar</div>
        </header>

          <x-calendar :posts="$posts" :month="$month" :year="$year" :months="$months" :calendar="$calendar" :yearRange="$yearRange" :startDayOfWeek="$startDayOfWeek" :daysInMonth="$daysInMonth"  />
    </div>

</x-layouts.main>
