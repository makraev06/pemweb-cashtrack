<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:100'],
            'account_type' => ['required', 'in:bank,ewallet,cash'],
            'balance' => ['required', 'numeric', 'min:0'],
        ]);

        Account::create([
            'user_id' => auth()->id(),
            'account_name' => $validated['account_name'],
            'account_type' => $validated['account_type'],
            'balance' => $validated['balance'],
        ]);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Sumber dana berhasil ditambahkan.');
    }

    public function update(Request $request, Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:100'],
            'account_type' => ['required', 'in:bank,ewallet,cash'],
            'balance' => ['required', 'numeric', 'min:0'],
        ]);

        $account->update($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Sumber dana berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);

        if ($account->transactions()->exists()) {
            return redirect()
                ->route('accounts.index')
                ->withErrors([
                    'account' => 'Sumber dana tidak bisa dihapus karena sudah punya transaksi.',
                ]);
        }

        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Sumber dana berhasil dihapus.');
    }
}