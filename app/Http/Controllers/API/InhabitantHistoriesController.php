<?php

namespace App\Http\Controllers\Api;

use App\Models\InhabitantHistories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\House;

class InhabitantHistoriesController extends Controller
{
    public function index()
    {
        return response()->json(
            InhabitantHistories::with(['resident', 'house'])->orderBy('created_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'house_id' => 'required|exists:houses,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $history = InhabitantHistories::create($data);

        House::where('id', $data['house_id'])->update([
            'is_occupied' => true,
        ]);

        return response()->json($history->load(['resident', 'house']), 201);
    }


    public function show($id)
    {
        $inhabitantHistories = InhabitantHistories::with(['resident', 'house'])->find($id);
        return response()->json($inhabitantHistories);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($data['end_date'] === '') {
            $data['end_date'] = null;
        }

        $inhabitant = InhabitantHistories::findOrFail($id);
        $inhabitant->update($data);

        $houseId = $inhabitant->house_id;
        $masihDihuni = InhabitantHistories::where('house_id', $houseId)
            ->whereNull('end_date')
            ->exists();

        if (!$masihDihuni) {
            House::where('id', $houseId)->update([
                'is_occupied' => false,
            ]);
        }

        return response()->json([
            'message' => 'Berhasil diperbarui',
            'data' => $inhabitant->load(['resident', 'house']),
        ]);
    }


    public function destroy($id)
    {
        InhabitantHistories::findOrFail($id)->delete();
        return response()->json(['message' => 'Inhabitant history deleted successfully']);
    }
}
