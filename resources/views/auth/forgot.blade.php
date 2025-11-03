<x-layouts.app>
    <div class="max-w-md mx-auto py-10 text-white">
        <h2 class="text-xl font-semibold mb-4">Forgot your password?</h2>
        <p class="text-gray-400 mb-6">Enter your email and we’ll send you a password reset link.</p>

        @if (session('status'))
            <div class="p-3 bg-green-500/20 border border-green-400 rounded mb-4">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" placeholder="Email address"
                class="w-full p-3 rounded bg-gray-700 border outline-none border-gray-600 mb-4" required>
            <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 rounded text-white font-medium">
                Send Reset Link
            </button>
        </form>
    </div>
</x-layouts.app>
