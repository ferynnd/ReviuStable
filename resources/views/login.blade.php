<x-layouts.app>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 max-h-screen h-screen ">
        <div class="h-full w-full flex justify-center items-center">
            <div class=" m-5 h-auto w-full">
                <div class="header-form max-w-sm mx-auto">
                    <img src="{{asset('assets/logo.png')}}" alt="" class="w-auto h-20 mx-auto">
                    <h3 class="text-white text-center text-2xl font-bold mt-3">
                        Wellcome, Back!!
                    </h3>
                    <form class="w-full mx-auto mt-7" action="{{route('login.submit')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="block mb-2 text-md font-medium text-white">Username</label>
                            <input type="username" name="username" id="username" value="{{ old("username") }}" class="border border-sky-400 text-white text-md rounded-md  block w-full p-4 focus:ring-0 focus:outline-none " placeholder="Masukan Username" required />
                        </div>
                        <div class="mb-7">
                            <label for="password" class="block mb-2 text-md font-medium text-white">Password</label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <input type="password" id="password" name="password" value="{{ old("password") }}"  x-bind:type="showPassword ? 'text' : 'password'"  class="border border-sky-400 text-white text-md rounded-md  block w-full p-4 focus:ring-0 focus:outline-none" placeholder="Masukan Password" required />
                                <button  class="absolute top-0 right-4 bottom-0" type="button" x-on:click="showPassword = !showPassword">
                                    <span class="text-white" x-html="showPassword ? `<i class='fa-solid fa-eye-slash'></i>` : `<i class='fa-solid fa-eye'></i>`"></span>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="text-white bg-gradient-to-b from-cyan-400 to-sky-500 hover:from-sky-500 hover:to-blue-600 focus:outline-none  rounded-md text-lg font-semibold w-full block px-5 py-4 text-center">Login</button>
                    </form>

                </div>
            </div>
            
        </div>
        <div class="hidden lg:block h-full w-full">
            <div class="max-h-screen h-full w-full overflow-hidden">
                    <img src="{{asset('assets/loginimg.png')}}" alt="login-img" class=" object-cover p-5 rounded-4xl ">
            </div>
        </div>

    </div>
</x-layouts.app>