<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function index()
    {
        return view('export.index');
    }

    public function api(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Export::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $exports = $query->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $exports->items(),
            'current_page' => $exports->currentPage(),
            'last_page' => $exports->lastPage(),
            'per_page' => $exports->perPage(),
            'total' => $exports->total(),
            'has_more' => $exports->hasMorePages(),
        ]);
    }

    public function download(Export $export)
    {
        if ($export->status !== 'completed' || ! Storage::exists($export->file_path)) {
            abort(404, 'File tidak ditemukan atau export belum selesai');
        }

        return Storage::download($export->file_path, $export->filename);
    }

    public function destroy(Export $export)
    {
        if (Storage::exists($export->file_path)) {
            Storage::delete($export->file_path);
        }

        $export->delete();

        return response()->json([
            'message' => 'Export berhasil dihapus',
        ]);
    }
}
