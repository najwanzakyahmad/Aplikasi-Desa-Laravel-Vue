<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Resources\DevelopmentResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentRepositoryInterface;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    private DevelopmentRepositoryInterface $developmentRepository;

    public function __construct(DevelopmentRepositoryInterface $developmentRepository) {
        $this->developmentRepository = $developmentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $developments = $this->developmentRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($developments, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $developments->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $developments->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($developments, 'count')) {
                // Collection
                $total = $developments->count();
            } else {
                // Fallback ke count() PHP
                $total = count($developments);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Berhasil mendapatkan seluruh data pengembangan. Total data: {$total}",
                DevelopmentResource::collection($developments),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    public function getAllPaginated(Request $request)
    {
        try {
            $request = $request->validate([
                'search' => 'nullable|string',
                'row_per_page' => 'required|integer'
            ]);

            $developments = $this->developmentRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data pengembangan.',
                PaginateResource::make($developments, DevelopmentResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = $request->validated();

        try {
            $developments = $this->developmentRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan pengembangan',
                new DevelopmentResource($developments),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $development = $this->developmentRepository->getById(
                $id
            );

            if(!$development){
                return ResponseHelper::jsonResponse(
                    false,
                    'Pengembangan tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan pengembangan berdasarkan ID',
                new DevelopmentResource($development),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validated();

        try {
            $development = $this->developmentRepository->getById(
                $id
            );

            if(!$development){
                return ResponseHelper::jsonResponse(
                    false,
                    'Pengembangan tidak ditemukan',
                    null,
                    404
                );
            }

            $development = $this->developmentRepository->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui pengembangan',
                new DevelopmentResource($development),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $development = $this->developmentRepository->getById($id);

            if(!$development){
                return ResponseHelper::jsonResponse(
                    false,
                    'Pengembangan tidak ditemukan',
                    null,
                    404
                );
            }

            $development = $this->developmentRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus pengembangan',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
