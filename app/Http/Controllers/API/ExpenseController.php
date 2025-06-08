<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return response()->json(Expense::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $expense = Expense::create($validated);

        return response()->json($expense, 201);
    }

    public function show(Expense $expense)
    {
        return response()->json($expense);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'amount' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
        ]);

        $expense->update($validated);

        return response()->json($expense);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
