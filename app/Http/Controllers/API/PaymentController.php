<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InhabitantHistories;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(
            Payment::with(['resident', 'house', 'payment_items'])->orderBy('created_at', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'resident_id'   => 'required|exists:residents,id',
            'total_amount'  => 'required|numeric|min:0',
            'note'          => 'nullable|string',
            'status'        => 'required|in:lunas,belum lunas',
            'payment_date'  => 'required|date',
            'items'         => 'required|array|min:1',
            'items.*.type'        => 'required|in:satpam,kebersihan',
            'items.*.amount'      => 'required|numeric|min:0',
            'items.*.start_date'  => 'required|date',
            'items.*.end_date'    => 'nullable|date|after_or_equal:items.*.start_date',
        ]);

        // Ambil house_id dari histori penghuni aktif
        $activeHistory = InhabitantHistories::where('resident_id', $data['resident_id'])
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', Carbon::now());
            })
            ->orderByDesc('start_date')
            ->first();

        if (!$activeHistory) {
            return response()->json(['message' => 'Penghuni tidak memiliki rumah aktif saat ini.'], 422);
        }

        $data['house_id'] = $activeHistory->house_id;

        DB::beginTransaction();
        try {
            // Simpan payment utama
            $payment = Payment::create([
                'resident_id'  => $data['resident_id'],
                'house_id'     => $data['house_id'],
                'total_amount' => $data['total_amount'],
                'note'         => $data['note'],
                'status'       => $data['status'],
                'payment_date' => $data['payment_date'],
            ]);

            // Simpan semua payment items
            foreach ($data['items'] as $item) {
                $payment->payment_items()->create([
                    'type'       => $item['type'],
                    'amount'     => $item['amount'],
                    'start_date' => $item['start_date'],
                    'end_date'   => $item['end_date'],
                ]);
            }

            DB::commit();
            return response()->json($payment->load(['resident', 'house', 'payment_items']), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan pembayaran.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $payment = Payment::with(['resident', 'house', 'payment_items'])->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'resident_id'   => 'required|exists:residents,id',
            'total_amount'  => 'required|numeric|min:0',
            'note'          => 'nullable|string',
            'status'        => 'required|in:lunas,belum lunas',
            'payment_date'  => 'required|date',
            'items'         => 'required|array|min:1',
            'items.*.type'        => 'required|in:satpam,kebersihan',
            'items.*.amount'      => 'required|numeric|min:0',
            'items.*.start_date'  => 'required|date',
            'items.*.end_date'    => 'nullable|date|after_or_equal:items.*.start_date',
        ]);

        // Ambil house_id dari histori penghuni aktif
        $activeHistory = InhabitantHistories::where('resident_id', $data['resident_id'])
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderByDesc('start_date')
            ->first();

        if (!$activeHistory) {
            return response()->json(['message' => 'Penghuni tidak memiliki rumah aktif saat ini.'], 422);
        }

        $data['house_id'] = $activeHistory->house_id;

        DB::beginTransaction();
        try {
            $payment = Payment::findOrFail($id);
            $totalAmount = collect($data['items'])->sum('amount');
            $data['total_amount'] = $totalAmount;

            // Update data utama pembayaran
            $payment->update([
                'resident_id'  => $data['resident_id'],
                'house_id'     => $data['house_id'],
                'total_amount' => $data['total_amount'],
                'note'         => $data['note'],
                'status'       => $data['status'],
                'payment_date' => $data['payment_date'],
            ]);

            // Hapus payment items lama
            $payment->payment_items()->delete();

            // Simpan ulang payment items baru
            foreach ($data['items'] as $item) {
                $payment->payment_items()->create([
                    'type'       => $item['type'],
                    'amount'     => $item['amount'],
                    'start_date' => $item['start_date'],
                    'end_date'   => $item['end_date'],
                ]);
            }

            DB::commit();
            return response()->json($payment->load(['resident', 'house', 'payment_items']), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui pembayaran.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
