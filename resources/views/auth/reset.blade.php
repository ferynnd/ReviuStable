<x-layouts.app>
    <div class="max-w-md mx-auto py-10 text-white">
        <h2 class="text-xl font-semibold mb-4">Reset your password</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <input type="email" name="email" value="{{ old('email', $email) }}"
                class="w-full p-3 rounded outline-none bg-gray-700 border border-gray-600 mb-4" readonly>

            <input type="password" name="password" placeholder="New password"
                class="w-full p-3 rounded outline-none bg-gray-700 border border-gray-600 mb-4" required>

            <input type="password" name="password_confirmation" placeholder="Confirm password"
                class="w-full p-3 rounded outline-none bg-gray-700 border border-gray-600 mb-4" required>

            <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 rounded text-white font-medium">
                Reset Password
            </button>
        </form>
    </div>
</x-layouts.app>
