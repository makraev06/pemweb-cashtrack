@extends('layouts.cashtrack')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Tambah Transaksi</h1>
        <p class="text-slate-500">Catat pemasukan atau pengeluaran.</p>
    </div>

    <form method="POST" action="{{ route('transactions.store') }}" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div>
            <label class="block mb-1 font-semibold">Jenis</label>
            <select name="jenis" id="jenis" class="w-full rounded-lg border-slate-300">
                <option value="">Pilih jenis</option>
                <option value="income" @selected(old('jenis') === 'income')>Dana Masuk</option>
                <option value="expense" @selected(old('jenis') === 'expense')>Dana Keluar</option>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Kategori</label>
            <select name="category" id="category" class="w-full rounded-lg border-slate-300">
                <option value="">Pilih jenis dulu</option>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Sumber Dana</label>
            <select name="account_id" class="w-full rounded-lg border-slate-300">
                <option value="">Pilih sumber dana</option>

                @foreach ($accounts as $account)
                    <option value="{{ $account->account_id }}" @selected(old('account_id') == $account->account_id)>
                        {{ $account->account_name }} —
                        Rp {{ number_format($account->balance, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>

            @if ($accounts->isEmpty())
                <p class="text-sm text-red-600 mt-2">
                    Kamu belum punya sumber dana.
                    <a href="{{ route('accounts.index') }}" class="underline">
                        Tambahkan dulu di sini.
                    </a>
                </p>
            @endif
        </div>

        <div id="jumlahWrapper">
            <label class="block mb-1 font-semibold">Jumlah</label>
            <input type="number" name="jumlah" value="{{ old('jumlah') }}" class="w-full rounded-lg border-slate-300">
        </div>

        <div id="assetWrapper" class="hidden space-y-5">
            <div>
                <label class="block mb-1 font-semibold">Nama Aset</label>
                <input type="text" name="asset_name" value="{{ old('asset_name') }}"
                    class="w-full rounded-lg border-slate-300">
            </div>

            <div>
                <label class="block mb-1 font-semibold">Jenis Aset</label>
                <select name="asset_type" class="w-full rounded-lg border-slate-300">
                    <option value="">Pilih jenis aset</option>
                    @foreach (['Kendaraan', 'Elektronik', 'Properti', 'Peralatan', 'Investasi', 'Lainnya'] as $type)
                        <option value="{{ $type }}" @selected(old('asset_type') === $type)>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Nilai Aset</label>
                <input type="number" name="asset_value" value="{{ old('asset_value') }}"
                    class="w-full rounded-lg border-slate-300">
            </div>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Keterangan</label>
            <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="w-full rounded-lg border-slate-300">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                class="w-full rounded-lg border-slate-300">
        </div>

        <div class="flex gap-3">
            <button class="bg-emerald-700 text-white px-4 py-2 rounded-lg">
                Simpan
            </button>

            <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-lg bg-slate-200">
                Batal
            </a>
        </div>
    </form>

    <script>
        const jenisInput = document.getElementById('jenis');
        const categoryInput = document.getElementById('category');
        const assetWrapper = document.getElementById('assetWrapper');
        const jumlahWrapper = document.getElementById('jumlahWrapper');

        const categories = {
            income: ['Gaji', 'Bonus', 'Penjualan', 'Investasi', 'Hadiah', 'Lainnya'],
            expense: ['Makanan', 'Transportasi', 'Tagihan', 'Belanja', 'Investasi', 'Pembelian Aset', 'Lainnya'],
        };

        const oldCategory = @json(old('category'));

        function renderCategories() {
            const jenis = jenisInput.value;
            categoryInput.innerHTML = '<option value="">Pilih kategori</option>';

            if (!categories[jenis]) {
                toggleAssetFields();
                return;
            }

            categories[jenis].forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;

                if (category === oldCategory) {
                    option.selected = true;
                }

                categoryInput.appendChild(option);
            });

            toggleAssetFields();
        }

        function toggleAssetFields() {
            const isAsset = categoryInput.value === 'Pembelian Aset';

            assetWrapper.classList.toggle('hidden', !isAsset);
            jumlahWrapper.classList.toggle('hidden', isAsset);
        }

        jenisInput.addEventListener('change', renderCategories);
        categoryInput.addEventListener('change', toggleAssetFields);

        renderCategories();
    </script>
@endsection