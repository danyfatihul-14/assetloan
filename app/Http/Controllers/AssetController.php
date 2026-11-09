<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $assets = Asset::query()
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->string('q')->isNotEmpty(), function ($query) use ($request): void {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($nested) => $nested->where('nama', 'like', $term)->orWhere('kode', 'like', $term));
            })
            ->latest('id')
            ->get();

        return response()->json($assets);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:100', 'unique:assets,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'kondisi' => ['required', Rule::in(['baik', 'rusak'])],
        ]);
        $data['kategori'] = $data['kategori'] ?? 'Umum';

        return response()->json(Asset::create($data), 201);
    }

    public function show(Asset $asset): JsonResponse
    {
        return response()->json($asset);
    }

    public function update(Request $request, Asset $asset): JsonResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'kondisi' => ['required', Rule::in(['baik', 'rusak'])],
        ]);
        $asset->update($data);

        return response()->json($asset->fresh());
    }

    public function destroy(Asset $asset): Response|JsonResponse
    {
        if ($asset->status === 'dipinjam') {
            return response()->json(['error' => 'Asset sedang dipinjam, tidak bisa dihapus'], 409);
        }
        if ($asset->loans()->exists()) {
            return response()->json(['error' => 'Asset memiliki riwayat peminjaman, tidak bisa dihapus'], 409);
        }
        $asset->delete();

        return response()->noContent();
    }
}
