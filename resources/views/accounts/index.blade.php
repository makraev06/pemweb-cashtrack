@extends('layouts.cashtrack')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Sumber Dana</h1>
            <p class="text-slate-500">Kelola rekening bank, e-wallet, dan cash.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4">Tambah Sumber Dana</h2>

            <form method="POST" action="{{ route('accounts.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-1 font-semibold">Nama</label>
                    <input type="text" name="account_name" value="{{ old('account_name') }}"
                        class="w-full rounded-lg border-slate-300" placeholder="Contoh: BCA, Dana, Cash">
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Tipe</label>
                    <select name="account_type" class="w-full rounded-lg border-slate-300">
                        <option value="">Pilih tipe</option>
                        <option value="bank" @selected(old('account_type') === 'bank')>Bank</option>
                        <option value="ewallet" @selected(old('account_type') === 'ewallet')>E-Wallet</option>
                        <option value="cash" @selected(old('account_type') === 'cash')>Cash</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Saldo Awal</label>
                    <input type="number" name="balance" value="{{ old('balance', 0) }}"
                        class="w-full rounded-lg border-slate-300">
                </div>

                <button class="w-full bg-emerald-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="text-left p-4">Nama</th>
                        <th class="text-left p-4">Tipe</th>
                        <th class="text-right p-4">Saldo</th>
                        <th class="text-right p-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($accounts as $account)
                        <tr class="border-t">
                            <td class="p-4 font-semibold">
                                {{ $account->account_name }}
                            </td>

                            <td class="p-4">
                                {{ strtoupper($account->account_type) }}
                            </td>

                            <td class="p-4 text-right">
                                Rp {{ number_format($account->balance, 0, ',', '.') }}
                            </td>

                            <td class="p-4 text-right">
                                <details class="inline-block text-left">
                                    <summary class="cursor-pointer text-emerald-700">
                                        Edit
                                    </summary>

                                    <div class="absolute right-8 mt-2 bg-white border rounded-xl shadow p-4 w-72 z-10">
                                        <form method="POST" action="{{ route('accounts.update', $account) }}" class="space-y-3">
                                            @csrf
                                            @method('PUT')

                                            <input type="text" name="account_name" value="{{ $account->account_name }}"
                                                class="w-full rounded-lg border-slate-300">

                                            <select name="account_type" class="w-full rounded-lg border-slate-300">
                                                <option value="bank" @selected($account->account_type === 'bank')>Bank</option>
                                                <option value="ewallet" @selected($account->account_type === 'ewallet')>E-Wallet
                                                </option>
                                                <option value="cash" @selected($account->account_type === 'cash')>Cash</option>
                                            </select>

                                            <input type="number" name="balance" value="{{ $account->balance }}"
                                                class="w-full rounded-lg border-slate-300">

                                            <button class="w-full bg-emerald-700 text-white px-3 py-2 rounded-lg">
                                                Update
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('accounts.destroy', $account) }}"
                                            onsubmit="return confirm('Hapus sumber dana ini?')" class="mt-2">
                                            @csrf
                                            @method('DELETE')

                                            <button class="w-full bg-red-600 text-white px-3 py-2 rounded-lg">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500">
                                Belum ada sumber dana.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection