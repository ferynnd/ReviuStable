<x-layouts.main>
    <div class="max-w-2xl mx-auto" x-data="weeklyForm()">
        <!-- Header -->
        <header class="h-20 flex items-center gap-7 px-6 border-b border-slate-700">
            <a onclick="history.back()" class="p-2 rounded-lg hover:bg-cyan-400/80 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left text-white">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 6l-6 6l6 6"/>
                </svg>
            </a>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="icon icon-tabler icons-tabler-outline icon-tabler-minus-vertical text-white">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 5v14"/>
                </svg>
            </span>
            <div class="text-lg font-semibold text-cyan-400">Weekly Content</div>
        </header>

        <!-- Main -->
        <main class="py-8 px-4 sm:px-4 mx-auto max-w-lg mb-14">
            <div x-data="{ earlyDate: '' }" class="flex flex-col items-center justify-center">
                <!-- Input Early Date -->
                <div class="space-y-2 w-4/6 text-center">
                    <label for="date" class="block text-sm font-semibold text-white">Set Early Date</label>
                <div class="relative">
                    <input
                        type="date"
                        id="date"
                        x-model="earlyDate"
                        @change="generateDates"
                        class="w-full px-4 py-2.5 rounded-lg fill-white border-2 border-gray-500 bg-slate-800
                            hover:bg-slate-800/70 outline-0 text-white placeholder-gray-400
                            focus:ring-sky-400 focus:border-sky-400 transition duration-150
                            pr-12 calendar-input"
                    >

                    <!-- Icon custom -->
                    <svg class="w-6 h-6 text-white absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                </div>

                <!-- Form Weekly -->
                <form method="POST" action="{{ route('post.weekly.store') }}"
                      class="flex flex-col space-y-4 mt-6 w-full h-auto">
                    @csrf

                    <div class="space-y-5 flex-1">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex flex-col gap-3 bg-gradient-to-r from-slate-700/50 to-transparant  rounded-lg p-4 border border-gray-600">
                                <label class="text-sm text-gray-300 font-medium" x-text="item.displayDate"></label>

                                <input
                                    type="hidden"
                                    name="post_at[]"
                                    :value="item.date"
                                >

                                <input
                                    type="text"
                                    name="title[]"
                                    x-model="item.title"
                                    placeholder="Add Title.."
                                    class="w-full text-sm px-3 py-3 rounded-lg border-2 border-gray-500 bg-slate-800
                                        text-white placeholder-gray-400 outline-0 ring-0 focus:border-sky-400
                                           transition duration-150"
                                    required
                                >
                            </div>
                        </template>
                    </div>

                    <button
                        x-show="earlyDate"
                        type="submit"
                        class="px-5 w-full py-2.5 bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white font-semibold rounded-lg focus:outline-none transition duration-150">
                        Selesai
                    </button>
                </form>
            </div>
        </main>
    </div>
@push('js')
    <script>
        function weeklyForm() {
            return {
                earlyDate: '',
                items: [],

                formatDateIndonesia(date) {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    const dayName = days[date.getDay()];
                    const day = date.getDate();
                    const monthName = months[date.getMonth()];
                    const year = date.getFullYear();

                    return `${dayName}, ${day} ${monthName} ${year}`;
                },

                generateDates() {
                    this.items = [];
                    if (!this.earlyDate) return;

                    const base = new Date(this.earlyDate);
                    for (let i = 0; i < 7; i++) {
                        const d = new Date(base);
                        d.setDate(base.getDate() + i);
                        this.items.push({
                            date: d.toISOString().split('T')[0], // Format YYYY-MM-DD untuk value
                            displayDate: this.formatDateIndonesia(d), // Format Indonesia untuk tampilan
                            title: ''
                        });
                    }
                }
            }
        }
    </script>
@endpush
</x-layouts.main>