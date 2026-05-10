<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-red-700">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Setelah akun dihapus, semua data akun akan hilang secara permanen.
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700">
        Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-slate-900">
                Yakin ingin menghapus akun?
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Masukkan password untuk mengonfirmasi penghapusan akun.
                Data akun tidak bisa dikembalikan setelah dihapus.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">
                    Password
                </label>

                <input id="password" name="password" type="password" class="w-full rounded-lg border-slate-300"
                    placeholder="Password">

                @error('password', 'userDeletion')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 font-semibold hover:bg-slate-300">
                    Batal
                </button>

                <button class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>