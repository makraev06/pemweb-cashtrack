@extends('layouts.cashtrack')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Assets</h1>
            <p class="text-slate-500">Kelola aset yang kamu miliki.</p>
        </div>

        <a href="{{ route('assets.create') }}" class="bg-emerald-700 text-white px-4 py-2 rounded-lg">
            Tambah Aset
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Total Nilai Aset</p>
            <h2 class="text-3xl font-bold text-emerald-700">
                Rp {{ number_format($totalAsset, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-slate-500">Jumlah Aset</p>
            <h2 class="text-3xl font-bold">
                {{ $totalItem }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100">
                <tr>
                    <th class="text-left p-4">Nama Aset</th>
                    <th class="text-left p-4">Kategori</th>
                    <th class="text-left p-4">Tanggal Perolehan</th>
                    <th class="text-right p-4">Nilai</th>
                    <th class="text-right p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($assets as $asset)
                    <tr class="border-t">
                        <td class="p-4">
                            <div class="font-semibold">{{ $asset->nama_aset }}</div>

                            @if ($asset->deskripsi)
                                <div class="text-sm text-slate-500">
                                    {{ $asset->deskripsi }}
                                </div>
                            @endif

                            @if ($asset->transaction_id)
                                <div class="text-xs text-emerald-700 mt-1">
                                    Dibuat dari transaksi Pembelian Aset
                                </div>
                            @endif
                        </td>

                        <td class="p-4">
                            {{ $asset->kategori }}
                        </td>

                        <td class="p-4">
                            {{ $asset->tanggal_perolehan->format('d/m/Y') }}
                        </td>

                        <td class="p-4 text-right">
                            Rp {{ number_format($asset->nilai, 0, ',', '.') }}
                        </td>

                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('assets.edit', $asset) }}" class="text-emerald-700">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('assets.destroy', $asset) }}"
                                    onsubmit="return confirm('Hapus aset ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">
                            Belum ada aset.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection