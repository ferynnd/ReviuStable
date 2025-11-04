<div class="flex grow flex-col gap-y-5 h-full overflow-y-auto px-12 border-r border-slate-700 bg-slate-900">
  <div class="flex h-auto shrink-0 items-center mt-5 mb-3">
    <img class="h-14 w-auto" src="{{asset('assets/logo.png')}}" alt="Your Company">
  </div>
  <nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
      <li>
        <ul role="list" class="-mx-2 space-y-1">
          <li>
            <a href="{{ route('home') }}"
              class="group flex gap-x-3 rounded-md p-3 text-sm/6 font-semibold
                      {{ request()->routeIs('home') ? 'bg-slate-800 text-sky-400' : 'text-slate-200 hover:text-sky-400 hover:bg-slate-800' }}">
              <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"> <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /> </svg>
              Home
            </a>
          </li>

          <li>
            <a href="{{ route('calendar.page') }}"
              class="group flex gap-x-3 rounded-md p-3 text-sm/6 font-semibold
                      {{ request()->routeIs('calendar.page') ? 'bg-slate-800 text-sky-400' : 'text-slate-200 hover:text-sky-400 hover:bg-slate-800' }}">
              <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"> <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /> </svg>
              Calendar
            </a>
          </li>

          @role('superadmin')
          <li>
            <a href="{{ route('users.index') }}"
              class="group flex gap-x-3 rounded-md p-3 text-sm/6 font-semibold
                      {{ request()->routeIs('users.*') ? 'bg-slate-800 text-sky-400' : 'text-slate-200 hover:text-sky-400 hover:bg-slate-800' }}">
              <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"> <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /> </svg>
              User
            </a>
          </li>
          <li>
            <a href="{{route('tags.index')}}"
              class="group flex gap-x-3 rounded-md p-3 text-sm/6 font-semibold
                      {{ request()->routeIs('tags.index') ? 'bg-slate-800 text-sky-400' : 'text-slate-200 hover:text-sky-400 hover:bg-slate-800' }}">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-hash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 9l14 0" /><path d="M5 15l14 0" /><path d="M11 4l-4 16" /><path d="M17 4l-4 16" /></svg>
              Hashtag
            </a>
          </li>
          @endrole


          @role('staff')
          <li>
            <a href="{{route('post.index')}}"
              class="group flex justify-center items-center gap-x-3 mt-5 rounded-md p-4 text-md/6 font-semibold bg-gradient-to-b from-cyan-400 to-sky-500 hover:from-sky-500 hover:to-blue-600 text-slate-200">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rocket size-7 shrink-0">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" />
                <path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" /><path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
              </svg> Post Content
            </a>
          </li>
          @endrole

        </ul>
      </li>

      @php
          $user = Auth()->user();
      @endphp
        <li class="-mx-6 mt-auto mb-3">
                <div class="w-full max-w-sm p-2 bg-slate-900 hover:bg-slate-800 rounded-lg shadow-lg">
        <div class="flex items-center justify-between gap-x-4 px-6 py-3 text-sm/6 font-semibold rounded-lg text-white">
            <a href="{{ route('profile.page' , $user->username )}}" class="flex items-center gap-x-4 group">
                <img class="size-10 object-cover rounded-full bg-slate-800" src="{{ $user->image ? asset('storage/' . $user->image) : "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" }}" alt="">
                <div class="flex flex-col flex-1">
                    <span class="group-hover:text-cyan-300 transition-colors">{{$user->fullname}}</span>
                    <span class="text-xs font-normal text-slate-100/70">Lihat Profile</span>
                </div>
            </a>

            <div x-data="{}">
              <form x-ref="logoutForm" action="{{ route('logout') }}" method="POST" @submit.prevent>
                  @csrf
                  <button
                      class="group flex items-center gap-x-3 rounded-md p-3 text-md/6 font-semibold
                             bg-gradient-to-b from-cyan-400 to-sky-500 hover:from-sky-500 hover:to-blue-600 text-slate-200
                             shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-0.5"
                      type="button"
                      @click="Swal.fire({
                          title: 'Anda yakin?',
                          text: 'Anda akan keluar dari sesi ini.',
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3B82F6',
                          cancelButtonColor: '#EF4444',
                          confirmButtonText: 'Ya, Logout!',
                          cancelButtonText: 'Batal'
                      }).then((result) => {
                          if (result.isConfirmed) {
                              $refs.logoutForm.submit();
                          }
                      })"
                  >
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout -mr-1">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                          <path d="M9 12h12l-3 -3" />
                          <path d="M18 15l3 -3" />
                      </svg>
                  </button>
              </form>
            </div>
        </div>
    </div>
        </li>
    </ul>
  </nav>
</div>
