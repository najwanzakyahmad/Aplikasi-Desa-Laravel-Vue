<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\DevelopmentApplicantStoreRequest;
use App\Http\Requests\DevelopmentApplicantUpdateRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentApplicantRepositoryInterface;
use Illuminate\Http\Request;

class DevelopmentApplicantController extends Controller
{
    private DevelopmentApplicantRepositoryInterface $developmentApplicantRepository;

    public function __construct(DevelopmentApplicantRepositoryInterface $developmentApplicantRepository) {
        $this->developmentApplicantRepository = $developmentApplicantRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $developmentApplicants = $this->developmentApplicantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($developmentApplicants, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $developmentApplicants->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $developmentApplicants->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($developmentApplicants, 'count')) {
                // Collection
                $total = $developmentApplicants->count();
            } else {
                // Fallback ke count() PHP
                $total = count($developmentApplicants);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Berhasil mendapatkan seluruh data penerima pengembangan. Total data: {$total}",
                DevelopmentApplicantResource::collection($developmentApplicants),
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

            $developmentApplicants = $this->developmentApplicantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data penerima pengembangan.',
                PaginateResource::make($developmentApplicants, DevelopmentApplicantResource::class),
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
    public function store(DevelopmentApplicantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $developmentApplicants = $this->developmentApplicantRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan penerima pengembangan',
                new DevelopmentApplicantResource($developmentApplicants),
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
            $developmentApplicant = $this->developmentApplicantRepository->getById(
                $id
            );

            if(!$developmentApplicant){
                return ResponseHelper::jsonResponse(
                    false,
                    'Penerima pengembangan tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan penerima pengembangan berdasarkan ID',
                new DevelopmentApplicantResource($developmentApplicant),
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
    public function update(DevelopmentApplicantUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $developmentApplicant = $this->developmentApplicantRepository->getById(
                $id
            );

            if(!$developmentApplicant){
                return ResponseHelper::jsonResponse(
                    false,
                    'Penerima Pengembangan tidak ditemukan',
                    null,
                    404
                );
            }

            $developmentApplicant = $this->developmentApplicantRepository->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui penerima pengembangan',
                new DevelopmentApplicantResource($developmentApplicant),
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
            $developmentApplicant = $this->developmentApplicantRepository->getById($id);

            if(!$developmentApplicant){
                return ResponseHelper::jsonResponse(
                    false,
                    'Penerima Pengembangan tidak ditemukan',
                    null,
                    404
                );
            }

            $developmentApplicant = $this->developmentApplicantRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus penerima pengembangan',
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
