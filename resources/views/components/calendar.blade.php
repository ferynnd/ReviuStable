@php
    use Carbon\Carbon;

    $DAYS = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
@endphp

<div class="antialiased min-h-screen py-12 px-4 sm:px-4 mb-12">
    <div class="max-w-5xl w-full mx-auto text-gray-100">

        <div class="bg-gray-800 border border-slate-800 p-4 rounded-xl shadow-lg flex flex-wrap justify-end items-center gap-2">
            <form method="GET" action="{{ route('calendar.page') }}" class="flex flex-wrap gap-2 items-center">

                <div class="relative md:w-42 w-full">
                    <select
                        name="month"
                        class="appearance-none w-full px-5 py-3 rounded-lg border-2 border-slate-800 bg-gray-600 text-gray-100 focus:ring-sky-400 focus:border-sky-400 pr-10 leading-tight"
                    >
                        @foreach ($months as $num => $name)
                            <option value="{{ $num }}" @selected($num == $month)>{{ $name }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 text-gray-300 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <div class="relative md:w-42 w-full">
                    <select
                        name="year"
                        class="appearance-none w-full px-5 py-3 rounded-lg border-2 border-slate-800 bg-gray-600 text-gray-100 focus:ring-sky-400 focus:border-sky-400 pr-10 leading-tight"
                    >
                        @foreach ($yearRange as $y)
                            <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 text-gray-300 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <button
                    type="submit"
                    class="px-10 text-md py-2.5 md:w-42 w-full
                    bg-gradient-to-b from-purple-500 to-violet-600 hover:from-violet-600 hover:to-indigo-700
                    text-white rounded-lg font-semibold transition duration-150">
                    Filter
                </button>
            </form>
        </div>

        <!-- KALENDER -->
        <div class="bg-slate-900 mt-5 border border-slate-800 rounded-xl shadow-lg overflow-hidden">

            <!-- Header Hari -->
            <div class="grid grid-cols-7 text-center py-3 bg-gray-800 border-b border-slate-700">
                @foreach ($DAYS as $day)
                    <div class="uppercase tracking-wide text-sm font-semibold text-slate-400">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Isi Tanggal -->
            <div class="grid grid-cols-7 text-sm">
                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="h-24 border border-slate-800 bg-slate-900"></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date = Carbon::create($year, $month, $day);
                        $isToday = $date->isToday();
                        $postCount = $calendar[$day]['posts']->count();
                    @endphp

                    <div class="h-24 border border-slate-800 p-2 relative hover:bg-slate-800/50 transition">
                        <a href="{{ route('calendar.list', ['date' => $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT)]) }}"
                            class="block w-8 h-8 flex items-center justify-center rounded-full mb-1 font-semibold transition relative
                            {{ $isToday ? 'bg-gradient-to-b from-purple-500 to-violet-600 text-white' : 'text-slate-200 hover:bg-blue-500/20' }}">
                            {{ $day }}

                            @if ($postCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $postCount }}
                                </span>
                            @endif
                        </a>
                    </div>

                @endfor
            </div>
        </div>
    </div>
</div>
