<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalAsset = $assets->sum('nilai');
        $totalItem = $assets->count();

        return view('assets.index', compact(
            'assets',
            'totalAsset',
            'totalItem'
        ));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'in:Kendaraan,Elektronik,Properti,Peralatan,Investasi,Lainnya'],
            'nilai' => ['required', 'numeric', 'min:1'],
            'tanggal_perolehan' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Asset::create([
            'user_id' => auth()->id(),
            'nama_aset' => $validated['nama_aset'],
            'kategori' => $validated['kategori'],
            'nilai' => $validated['nilai'],
            'tanggal_perolehan' => $validated['tanggal_perolehan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Asset $asset)
    {
        abort_if($asset->user_id !== auth()->id(), 403);

        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        abort_if($asset->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'nama_aset' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'in:Kendaraan,Elektronik,Properti,Peralatan,Investasi,Lainnya'],
            'nilai' => ['required', 'numeric', 'min:1'],
            'tanggal_perolehan' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $asset->update($validated);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        abort_if($asset->user_id !== auth()->id(), 403);

        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}