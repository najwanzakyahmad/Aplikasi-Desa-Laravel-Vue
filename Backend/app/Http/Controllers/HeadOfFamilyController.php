<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\HeadOfFamilyStoreRequest;
use App\Http\Requests\HeadOfFamilyUpdateRequest;
use App\Http\Resources\HeadOfFamilyResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\HeadOfFamilyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class HeadOfFamilyController extends Controller implements HasMiddleware
{
    private HeadOfFamilyRepositoryInterface $headOfFamilyRepository;

    public function __construct(HeadOfFamilyRepositoryInterface $headOfFamilyRepository)
    {
        $this->headOfFamilyRepository = $headOfFamilyRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using([
                'head-of-family-list|head-of-family-menu|head-of-family-create|head-of-family-edit|head-of-family-delete'
            ]), only: ['index', 'getAllPaginated', 'show']),

            new Middleware(PermissionMiddleware::using(['head-of-family-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['head-of-family-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['head-of-family-delete']), only: ['destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($headOfFamilies, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $headOfFamilies->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $headOfFamilies->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($headOfFamilies, 'count')) {
                // Collection
                $total = $headOfFamilies->count();
            } else {
                // Fallback ke count() PHP
                $total = count($headOfFamilies);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Success Get All Data Head Of Families. Total data: {$total}",
                HeadOfFamilyResource::collection($headOfFamilies),
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
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Success Get All Data head Of Families',
                PaginateResource::make($headOfFamilies, HeadOfFamilyResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Gagal mendapatkan data head of families',
                null,
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HeadOfFamilyStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $headOfFamilies = $this->headOfFamilyRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Kepala Keluarga Berhasil ditambahkan',
                new HeadOfFamilyResource($headOfFamilies),
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
            $headOfFamily = $this->headOfFamilyRepository->getById(
                $id
            );

            if(!$headOfFamily){
                return ResponseHelper::jsonResponse(
                    false,
                    'Kepala keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan kepala keluarga berdasarkan ID',
                new HeadOfFamilyResource($headOfFamily),
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
    public function update(HeadOfFamilyUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $headOfFamily = $this->headOfFamilyRepository->getById(
                $id
            );

            if(!$headOfFamily){
                return ResponseHelper::jsonResponse(
                    false,
                    'Kepala keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            $headOfFamily = $this->headOfFamilyRepository->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui kepala keluarga',
                new HeadOfFamilyResource($headOfFamily),
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
            $headOfFamily = $this->headOfFamilyRepository->getById($id);

            if(!$headOfFamily){
                return ResponseHelper::jsonResponse(
                    false,
                    'Kepala keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            $headOfFamily = $this->headOfFamilyRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus kepala keluarga',
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
