@props(['user'])

<div class="bg-gray-700/70 text-gray-100 rounded-2xl border-2 border-gray-600 shadow-lg p-1 w-full mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between py-1 px-2">
        <div class="flex space-x-2 items-center">
            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
            <span class="text-xs text-white uppercase">{{ $user->roles->first()?->name  }}</span>
        </div>
        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="text-gray-400 hover:text-white focus:outline-none p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 rounded-lg shadow-2xl overflow-clip bg-gray-700 ring-1 ring-gray-600 z-60">
                @role(['superadmin'])
                   <form 
                        action="{{ route('users.destroy', $user->id) }}" 
                        method="POST" 
                        x-ref="deleteForm"
                        @submit.prevent
                        class="inline"
                    >
                        @csrf
                        @method('DELETE')

                        <button 
                            type="button" 
                            class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-400/20 rounded-t-lg w-full text-left"
                            @click="
                                Swal.fire({
                                    title: 'Anda yakin?',
                                    text: 'Apakah anda yakin ingin menghapus user ini?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3B82F6',
                                    cancelButtonColor: '#EF4444',
                                    confirmButtonText: 'Ya, Hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $refs.deleteForm.submit();
                                    }
                                })
                            "
                        >
                            Hapus
                        </button>
                    </form>
                    <a href="{{route('users.edit', $user)}}" class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Edit</a>
                @endrole
            </div>
        </div>
    </div>

    <div class="bg-slate-800 text-gray-100 rounded-xl  p-3 w-full mx-auto">
        <!-- Content -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold">{{ $user->fullname }}</h2>
                <p class="text-slate-400 text-sm">{{'@'. $user->username }}</p>
            </div>

            <!-- Avatar -->
            <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center">
                <img class="size-10 rounded-full bg-slate-800" src="{{ $user->image ? asset('storage/' . $user->image) : "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" }}" alt="">
            </div>
        </div>

        <!-- Email -->
        <div class="mt-4 text-sm flex justify-between">
            <p class="text-slate-400">Email</p>
            <p class="font-medium text-gray-200">{{ $user->email }}</p>
        </div>

        <!-- Status -->
        <div class="mt-3 text-sm flex justify-between">
            <p class="text-slate-400 mb-1">Status</p>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                {{ $user->is_active ? 'bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white' : 'bg-gradient-to-b from-red-400 to-red-600 hover:from-red-500 hover:to-orange-700 text-white' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
            
    </div>


</div>
