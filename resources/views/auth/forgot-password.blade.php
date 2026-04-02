<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lupa Password</h2>
        <p class="text-sm text-gray-500 mt-1">
            Masukkan email admin. Kami akan mengirimkan link reset password jika fitur email aktif.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="'Email'" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="email"
                          class="block mt-1 w-full rounded-xl border-gray-300 border-auth"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <button type="submit"
                class="w-full btn-auth text-white font-semibold py-3 rounded-xl">
            Kirim Link Reset Password
        </button>
    </form>
</x-guest-layout>