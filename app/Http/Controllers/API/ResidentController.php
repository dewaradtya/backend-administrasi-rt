<?php

namespace App\Http\Controllers\API;

use App\Models\Resident;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\InhabitantHistories;
use Carbon\Carbon;

class ResidentController extends Controller
{
    public function index()
    {
        return response()->json(Resident::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'ktp_photo' => 'required|image|max:2048',
            'status' => 'required|in:kontrak,tetap',
            'phone' => 'required|string',
            'is_married' => 'required|boolean',
            'house_id' => 'nullable|exists:houses,id',
        ]);

        if ($request->hasFile('ktp_photo')) {
            $path = $request->file('ktp_photo')->store('ktp_photos', 'public');
            $validated['ktp_photo'] = $path;
        }

        $resident = Resident::create($validated);

        if (!empty($validated['house_id'])) {
            InhabitantHistories::create([
                'resident_id' => $resident->id,
                'house_id' => $validated['house_id'],
                'start_date' => Carbon::today(),
                'end_date' => null,
            ]);

            House::where('id', $validated['house_id'])->update([
                'is_occupied' => true,
            ]);
        }

        return response()->json($resident, 201);
    }

    public function show($id)
    {
        $resident = Resident::findOrFail($id);
        return response()->json($resident);
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::findOrFail($id);

        $data = $request->except('ktp_photo');

        if ($request->hasFile('ktp_photo')) {
            $file = $request->file('ktp_photo');
            $path = $file->store('ktp_photos', 'public');
            $data['ktp_photo'] = $path;
        }

        $resident->update($data);

        return response()->json($resident);
    }

    public function destroy($id)
    {
        Resident::findOrFail($id)->delete();
        return response()->json(['message' => 'Resident deleted successfully']);
    }

    public function getActiveResidents()
    {
        $residents = Resident::whereHas('inhabitantHistories', function ($query) {
            $query->whereNull('end_date');
        })->get();

        return response()->json($residents);
    }
}
