<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\FamilyMemberStoreRequest;
use App\Http\Requests\FamilyMemberUpdateRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Repositories\FamilyMemberRepository;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    private FamilyMemberRepositoryInterface $familyMemberRepository;

    public function __construct(FamilyMemberRepositoryInterface $familyMemberRepository)
    {
        $this->familyMemberRepository = $familyMemberRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $familyMembers = $this->familyMemberRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($familyMembers, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $familyMembers->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $familyMembers->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($familyMembers, 'count')) {
                // Collection
                $total = $familyMembers->count();
            } else {
                // Fallback ke count() PHP
                $total = count($familyMembers);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Berhasil mendapatkan semua data Anggota Keluarga. Total data: {$total}",
                FamilyMemberResource::collection($familyMembers),
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

        $familyMembers = $this->familyMemberRepository->getAllPaginated(
            $request['search'] ?? null,
            $request['row_per_page']
        );

        return ResponseHelper::jsonResponse(
            true,
            'Berhasil mendapatkan data Anggota Keluarga.',
            PaginateResource::make($familyMembers, FamilyMemberResource::class),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamilyMemberStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                ' Anggota Keluarga Berhasil ditambahkan',
                new FamilyMemberResource($familyMember),
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
            $familyMember = $this->familyMemberRepository->getById(
                $id
            );

            if(!$familyMember){
                return ResponseHelper::jsonResponse(
                    false,
                    'Anggota keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan anggota keluarga berdasarkan ID',
                new FamilyMemberResource($familyMember),
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
    public function update(FamilyMemberUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->getById(
                $id
            );

            if(!$familyMember){
                return ResponseHelper::jsonResponse(
                    false,
                    'Anggota keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            $familyMember = $this->familyMemberRepository->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui anggota keluarga',
                new FamilyMemberResource($familyMember),
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
            $familyMember = $this->familyMemberRepository->getById($id);

            if(!$familyMember){
                return ResponseHelper::jsonResponse(
                    false,
                    'Anggota keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            $familyMember = $this->familyMemberRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus anggota keluarga',
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
