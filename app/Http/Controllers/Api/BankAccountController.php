<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        return BankAccount::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'bank_name' => 'required|string',
            'account_number' => 'nullable|string',
            'iban' => 'nullable|string',
            'is_active' => 'boolean',
            'is_online_default' => 'boolean',
            'type' => 'nullable|string|in:bank,wallet,pos,cash',
        ]);

        if ($validated['is_online_default'] ?? false) {
            BankAccount::where('is_online_default', true)->update(['is_online_default' => false]);
        }

        return BankAccount::create($validated);
    }

    public function show(BankAccount $bankAccount)
    {
        return $bankAccount;
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'bank_name' => 'sometimes|required|string',
            'account_number' => 'nullable|string',
            'iban' => 'nullable|string',
            'is_active' => 'boolean',
            'is_online_default' => 'boolean',
            'type' => 'nullable|string|in:bank,wallet,pos,cash',
        ]);

        if ($validated['is_online_default'] ?? false) {
            BankAccount::where('id', '!=', $bankAccount->id)
                ->where('is_online_default', true)
                ->update(['is_online_default' => false]);
        }

        $bankAccount->update($validated);
        return $bankAccount;
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return response()->noContent();
    }
}
