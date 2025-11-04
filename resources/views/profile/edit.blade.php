<x-layouts.main>
@include('components.alert-session')
    <div class="max-w-2xl mx-auto px-4" x-data="profileEditor()">
        <header class="h-20 flex items-center gap-7 px-6 border-b border-slate-700 ">
            <a onclick="history.back()" class="p-2 rounded-lg hover:bg-cyan-400/80 transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
            </a>
            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-minus-vertical text-white"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /></svg></span>
            <div class="text-lg font-semibold text-cyan-400">Edit Profile</div>
        </header>


        <!-- TOMBOL EDIT YANG BENAR -->
        <div class="flex justify-between items-center my-5 md:my-10">
            <span class="text-white text-lg font-semibold">My Profile</span>
            <button type="button"
                    @click="toggleEdit()"
                    :class="isEditing ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-700 hover:bg-gray-600'"
                    class="transition-colors flex gap-2 items-center text-white font-medium border border-gray-500 rounded-lg px-4 py-2">
                <span x-text="isEditing ? 'Batal Edit' : 'Edit'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    <path d="M16 5l3 3" />
                </svg>
            </button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="flex items-start my-5 space-x-4 p-5 bg-gray-800/40 border border-gray-600 rounded-xl">
                <div class="relative">
                    <img
                         src="{{ $user->image ? asset('storage/' . $user->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->fullname).'&background=0D8ABC&color=fff' }}"
                        class="lg:w-20 lg:h-20 w-16 h-16 object-cover rounded-full border-2 border-slate-700 shadow"
                        alt="{{ $user->fullname }}"
                        id="profileImage">
                    <input type="file" id="imageInput" name="image" accept="image/*" class="hidden" @change="previewImage">
                    <button type="button"
                            @click="document.getElementById('imageInput').click()"
                            x-show="isEditing"
                            class="absolute bottom-0 right-0 bg-green-500 hover:bg-green-600 rounded-full p-1 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-camera text-white">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                            <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col items-start py-3">
                    <h2 class="text-lg lg:text-xl font-semibold text-slate-100">{{ $user->fullname }}</h2>
                    <p class="text-slate-400 text-sm lg:text-md">{{ '@'.$user->username }}</p>
                </div>
            </section>

            <div class="bg-gray-800/40 border border-gray-600 rounded-xl p-5 text-white">
                <h4 class="font-medium mb-2">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-slate-400 text-sm">Fullname</label>
                        <input type="text"
                               name="fullname"
                               :disabled="!isEditing"
                               :class="isEditing ? 'border-green-500 bg-gray-700' : 'border-gray-600 bg-gray-700/40'"
                               class="w-full mt-2 rounded-md text-white p-3 outline-none border transition-colors"
                               value="{{ old('fullname', $user->fullname) }}">
                    </div>
                    <div>
                        <label class="text-slate-400 text-sm">Username</label>
                        <input type="text"
                               name="username"
                               :disabled="!isEditing"
                               :class="isEditing ? 'border-green-500 bg-gray-700' : 'border-gray-600 bg-gray-700/40'"
                               class="w-full mt-2 rounded-md text-white p-3 outline-none border transition-colors"
                               value="{{ old('username', $user->username) }}">
                    </div>
                    <div>
                        <label class="text-slate-400 text-sm">Email</label>
                        <input type="email"
                               name="email"
                               :disabled="!isEditing"
                               :class="isEditing ? 'border-green-500 bg-gray-700' : 'border-gray-600 bg-gray-700/40'"
                               class="w-full mt-2 rounded-md text-white p-3 outline-none border transition-colors"
                               value="{{ old('email', $user->email) }}">
                    </div>

                    <div
                        x-data="{
                            bio: @js(old('bio', $user->bio ?? '')),
                            max: 150
                        }"
                    >
                        <div class="flex justify-between items-center">
                            <label class="text-slate-400 text-sm">Bio</label>
                            <span
                                class="text-xs"
                                :class="bio.length > 130 ? 'text-red-500' : 'text-slate-500'"
                                x-text="bio.length + ' / ' + max"
                            ></span>
                        </div>

                        <textarea
                            name="bio"
                            rows="4"
                            maxlength="150"
                            x-model="bio"
                            :disabled="!isEditing"
                            :class="isEditing ? 'border-green-500 bg-gray-700' : 'border-gray-600 bg-gray-700/40'"
                            class="w-full mt-2 rounded-md text-white p-3 outline-none border transition-colors resize-none"
                        ></textarea>
                    </div>
                </div>

                <div x-show="isEditing" class="mt-6 flex justify-end gap-3">
                    <button type="submit"
                            class="px-4 py-3 text-md bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white rounded-lg transition-colors">
                        Simpan Perubahan
                    </button>
                </div>

            </div>


        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-0.5 lg:gap-2 mb-24">
            <div class="hidden md:block"></div>
            <div class="hidden md:block"></div>
            <div class="hidden md:block"></div>
            <a  @click.prevent="open = true" class="group flex justify-center items-center gap-x-3 mt-5 rounded-md p-4 text-sm font-semibold bg-gradient-to-b from-cyan-400 to-sky-500 hover:from-sky-500 hover:to-blue-600 text-slate-200">
                Change Password
            </a>
            {{-- <a href="{{ route('password.request')}}" class="group flex justify-center items-center gap-x-3 mt-5 rounded-md p-4 text-sm font-semibold bg-gradient-to-b from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-slate-200">
                Forgot Password
            </a> --}}
        </div>

            <!-- Modal -->
            <div
                x-show="open"
                x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
            >
                <div
                    @click.away="open = false"
                    class="bg-slate-800 border border-gray-600 rounded-lg  p-6 w-full max-w-md"
                >
                    <h2 class="text-lg font-semibold mb-4 text-white">Change Password</h2>

                    <form
                        method="POST"
                        action="{{ route('profile.change-password') }}"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-400 mb-1">Current Password</label>
                            <input  name="old_password" x-model="old_password"
                                class="w-full rounded-md text-white p-3 outline-none border transition-colors border-gray-600 bg-gray-700/40" required>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-400 mb-1">New Password</label>
                            <input  name="new_password" x-model="new_password"
                                class="w-full rounded-md text-white p-3 outline-none border transition-colors border-gray-600 bg-gray-700/40" required>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-400 mb-1">Confirm New Password</label>
                            <input  name="confirm_password" x-model="confirm_password"
                                class="w-full rounded-md text-white p-3 outline-none border transition-colors border-gray-600 bg-gray-700/40" required>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="open = false"
                                class="px-4 py-2 bg-gray-200 text-black rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-gradient-to-b from-green-400 to-green-600 hover:from-green-500 hover:to-emerald-700 text-white rounded-md ">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

    </div>

    <script>
        function profileEditor() {
            return {
                isEditing: false,
                open: false,
                old_password: '',
                new_password: '',
                confirm_password: '',

                init() {
                    // Inisialisasi jika diperlukan
                },

                toggleEdit() {
                    this.isEditing = !this.isEditing;
                },

                previewImage(event) {
                    const input = event.target;
                    const preview = document.getElementById('profileImage');

                    if (input.files && input.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            }
        }
    </script>
</x-layouts.main>
