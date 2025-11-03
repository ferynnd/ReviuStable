<x-layouts.main>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <header class="h-20 flex items-center gap-7 px-6 border-b border-slate-700">
            <a onclick="history.back()" class="p-2 rounded-lg hover:bg-cyan-400/80 transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-white icon icon-tabler icon-tabler-chevron-left">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 6l-6 6l6 6" />
                </svg>
            </a>

            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-white icon icon-tabler icon-tabler-minus-vertical">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 5v14" />
                </svg>
            </span>

            <div class="text-lg font-semibold text-cyan-400">
                {{ isset($user) ? 'Edit User' : 'User Create' }}
            </div>
        </header>

        <main class="py-8 px-4 sm:px-4 mx-auto max-w-md">
            <div class="flex flex-col items-center justify-center">
                
                @isset($user)
                    <div class="flex justify-center mb-6">
                        <img src="{{ $user->image ?: asset('images/default-avatar.png') }}"
                            alt="{{ $user->fullname }}"
                            class="w-28 h-28 rounded-full border-4 border-slate-600 object-cover shadow-lg">
                    </div>
                @endisset

                <form
                    action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
                    method="POST"
                    class="space-y-6 w-full"
                >
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <div class="flex flex-col space-y-2">
                        <label for="fullname" class="text-sm font-semibold text-white">Fullname</label>
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            value="{{ old('fullname', $user->fullname ?? '') }}"
                            placeholder="Masukkan fullname..."
                            class="w-full px-4 py-2.5 rounded-lg border-2 
                                @error('fullname') border-red-500 @else border-gray-500 @enderror
                                bg-slate-800 hover:bg-slate-800/70 text-white placeholder-gray-400
                                focus:ring-sky-400 focus:border-sky-400 outline-none transition duration-150"
                        >
                        @error('fullname')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="username" class="text-sm font-semibold text-white">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', $user->username ?? '') }}"
                            placeholder="Masukkan username..."
                            class="w-full px-4 py-2.5 rounded-lg border-2 
                                @error('username') border-red-500 @else border-gray-500 @enderror
                                bg-slate-800 hover:bg-slate-800/70 text-white placeholder-gray-400
                                focus:ring-sky-400 focus:border-sky-400 outline-none transition duration-150"
                        >
                        @error('username')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="email" class="text-sm font-semibold text-white">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 bg-gray-500 rounded-s-lg left-0 flex items-center p-2 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icon-tabler-at">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" />
                                </svg>
                            </span>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email ?? '') }}"
                                placeholder="Masukkan email..."
                                class="w-full pl-12 pr-4 py-2.5 rounded-lg border-2 
                                    @error('email') border-red-500 @else border-gray-500 @enderror
                                    bg-slate-800 hover:bg-slate-800/70 text-white placeholder-gray-400
                                    focus:ring-sky-400 focus:border-sky-400 outline-none transition duration-150"
                            >
                        </div>
                        @error('email')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="role" class="text-sm font-semibold text-white">Role</label>
                        <select
                            id="role"
                            name="role"
                            class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-500
                                bg-slate-800 text-white focus:ring-sky-400 focus:border-sky-400 outline-none transition duration-150"
                        >
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ old('role', isset($user) ? $user->roles->first()->name ?? '' : '') === $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>


                    @isset($user)
                        <div class="flex flex-col space-y-2">
                            <label for="status" class="text-sm font-semibold text-white">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-500
                                    bg-slate-800 text-white focus:ring-sky-400 focus:border-sky-400 outline-none transition duration-150"
                            >
                                <option value="1" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    @endisset

                    <button
                        type="submit"
                        class="px-5 w-full py-2.5 bg-gradient-to-b from-green-400 to-green-600
                            hover:from-green-500 hover:to-emerald-700 text-white font-semibold rounded-lg
                            focus:outline-none transition duration-150">
                        {{ isset($user) ? 'Update' : 'Selesai' }}
                    </button>
                </form>
            </div>
        </main>
    </div>
</x-layouts.main>
