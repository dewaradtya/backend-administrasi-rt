<?php

namespace App\Http\Controllers\Api;

use App\Models\House;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HouseController extends Controller
{
    public function index()
    {
        return response()->json(House::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'house_number' => 'required|unique:houses',
            'is_occupied' => 'boolean',
        ]);

        $house = House::create($data);

        return response()->json($house, 201);
    }

    public function show($id)
    {
        $house = House::with(['inhabitantHistories.resident', 'payments.resident'])->findOrFail($id);
        return response()->json($house);
    }

    public function update(Request $request, House $house)
    {
        $data = $request->validate([
            'house_number' => 'required|unique:houses,house_number,' . $house->id,
            'is_occupied' => 'boolean',
        ]);

        $house->update($data);

        return response()->json($house);
    }

    public function destroy($id)
    {
        House::findOrFail($id)->delete();

        return response()->json(['message' => 'House deleted successfully']);
    }
}
