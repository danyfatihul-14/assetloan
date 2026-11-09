<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $loans = Loan::with('asset')
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('id')
            ->get();

        return response()->json($loans);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'asset_id' => ['required', 'integer', 'exists:assets,id'],
            'peminjam' => ['required', 'string', 'max:255'],
            'keperluan' => ['nullable', 'string'],
            'tgl_rencana_kembali' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $loan = DB::transaction(function () use ($data): Loan {
            $asset = Asset::lockForUpdate()->findOrFail($data['asset_id']);
            if ($asset->kondisi === 'rusak') {
                abort(409, 'Asset dalam kondisi rusak');
            }
            if ($asset->status === 'dipinjam') {
                abort(409, 'Asset sedang dipinjam');
            }
            $loan = Loan::create($data);
            $asset->update(['status' => 'dipinjam']);

            return $loan;
        });

        return response()->json($loan->load('asset'), 201);
    }

    public function show(Loan $loan): JsonResponse
    {
        return response()->json($loan->load('asset'));
    }

    public function returnLoan(Loan $loan): JsonResponse
    {
        if ($loan->status === 'dikembalikan') {
            return response()->json(['error' => 'Asset sudah dikembalikan'], 409);
        }

        DB::transaction(function () use ($loan): void {
            $loan->update(['status' => 'dikembalikan', 'tgl_kembali' => now()]);
            $loan->asset()->update(['status' => 'tersedia']);
        });

        return response()->json($loan->fresh()->load('asset'));
    }
}
