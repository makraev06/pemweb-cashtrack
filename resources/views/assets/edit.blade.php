@extends('layouts.cashtrack')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Edit Aset</h1>
        <p class="text-slate-500">Perbarui data aset.</p>
    </div>

    <form method="POST" action="{{ route('assets.update', $asset) }}" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 font-semibold">Nama Aset</label>
            <input type="text" name="nama_aset" value="{{ old('nama_aset', $asset->nama_aset) }}"
                class="w-full rounded-lg border-slate-300">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Kategori</label>
            <select name="kategori" class="w-full rounded-lg border-slate-300">
                <option value="">Pilih kategori</option>

                @foreach (['Kendaraan', 'Elektronik', 'Properti', 'Peralatan', 'Investasi', 'Lainnya'] as $kategori)
                    <option value="{{ $kategori }}" @selected(old('kategori', $asset->kategori) === $kategori)>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Nilai Aset</label>
            <input type="number" name="nilai" value="{{ old('nilai', $asset->nilai) }}"
                class="w-full rounded-lg border-slate-300">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Tanggal Perolehan</label>
            <input type="date" name="tanggal_perolehan"
                value="{{ old('tanggal_perolehan', $asset->tanggal_perolehan->format('Y-m-d')) }}"
                class="w-full rounded-lg border-slate-300">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                class="w-full rounded-lg border-slate-300">{{ old('deskripsi', $asset->deskripsi) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button class="bg-emerald-700 text-white px-4 py-2 rounded-lg">
                Update
            </button>

            <a href="{{ route('assets.index') }}" class="px-4 py-2 rounded-lg bg-slate-200">
                Batal
            </a>
        </div>
    </form>
@endsection