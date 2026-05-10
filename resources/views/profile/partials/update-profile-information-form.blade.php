<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">
            Informasi Profile
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Perbarui nama dan email akun kamu.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block mb-1 font-semibold text-slate-700">
                Nama
            </label>

            <input id="name" name="name" type="text" class="w-full rounded-lg border-slate-300"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">

            @error('name')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="block mb-1 font-semibold text-slate-700">
                Email
            </label>

            <input id="email" name="email" type="email" class="w-full rounded-lg border-slate-300"
                value="{{ old('email', $user->email) }}" required autocomplete="username">

            @error('email')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="rounded-lg bg-amber-50 text-amber-800 p-4">
                <p class="text-sm">
                    Email kamu belum diverifikasi.

                    <button form="send-verification" class="underline text-sm font-semibold hover:text-amber-900">
                        Kirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-emerald-700">
                        Link verifikasi baru sudah dikirim ke email kamu.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <button class="bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-emerald-800">
                Simpan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-700 font-semibold">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>