<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
        <p class="text-sm text-gray-500 mt-1">Masukkan password baru untuk akun admin.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="'Email'" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="email"
                          class="block mt-1 w-full rounded-xl border-gray-300 border-auth"
                          type="email"
                          name="email"
                          :value="old('email', $request->email)"
                          required
                          autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password" :value="'Password Baru'" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="password"
                          class="block mt-1 w-full rounded-xl border-gray-300 border-auth"
                          type="password"
                          name="password"
                          required />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="'Konfirmasi Password'" class="text-gray-700 font-medium mb-2" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full rounded-xl border-gray-300 border-auth"
                          type="password"
                          name="password_confirmation"
                          required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <button type="submit"
                class="w-full btn-auth text-white font-semibold py-3 rounded-xl">
            Reset Password
        </button>
    </form>
</x-guest-layout>