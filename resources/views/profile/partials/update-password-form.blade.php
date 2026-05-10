<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Gunakan password yang panjang dan aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block mb-1 font-semibold text-slate-700">
                Password Saat Ini
            </label>

            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full rounded-lg border-slate-300" autocomplete="current-password">

            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block mb-1 font-semibold text-slate-700">
                Password Baru
            </label>

            <input id="update_password_password" name="password" type="password"
                class="w-full rounded-lg border-slate-300" autocomplete="new-password">

            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block mb-1 font-semibold text-slate-700">
                Konfirmasi Password Baru
            </label>

            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full rounded-lg border-slate-300" autocomplete="new-password">

            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button class="bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-emerald-800">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-700 font-semibold">
                    Password diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>