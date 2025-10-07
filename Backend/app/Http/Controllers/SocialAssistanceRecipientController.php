<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipientUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceRecipientController extends Controller implements HasMiddleware
{
    private SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository;

    public function __construct(SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository) {
        $this->socialAssistanceRecipientRepository = $socialAssistanceRecipientRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using([
                'social-assistance-recipient-list|social-assistance-recipient-menu|social-assistance-recipient-create|social-assistance-recipient-edit|social-assistance-recipient-delete'
            ]), only: ['index', 'getAllPaginated', 'show']),

            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-delete']), only: ['destroy'])
            ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($socialAssistanceRecipients, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $socialAssistanceRecipients->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $socialAssistanceRecipients->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($socialAssistanceRecipients, 'count')) {
                // Collection
                $total = $socialAssistanceRecipients->count();
            } else {
                // Fallback ke count() PHP
                $total = count($socialAssistanceRecipients);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Berhasil mendapatkan seluruh data penerima bantuan sosial. Total data: {$total}",
                SocialAssistanceRecipientResource::collection($socialAssistanceRecipients),
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
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data penerima bantuan sosial',
                PaginateResource::make($socialAssistanceRecipients, SocialAssistanceRecipientResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Gagal mendapatkan data penerima bantuan sosial',
                null,
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Penerima Bantuan Sosial Berhasil ditambahkan',
                new SocialAssistanceRecipientResource($socialAssistanceRecipient),
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
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById(
                $id
            );

            if(!$socialAssistanceRecipient){
                return ResponseHelper::jsonResponse(
                    false,
                    'Penerima bantuan sosial tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan penerima bantuan sosial berdasarkan ID',
                new SocialAssistanceRecipientResource($socialAssistanceRecipient),
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
    public function update(SocialAssistanceRecipientUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById(
                $id
            );

            if(!$socialAssistanceRecipient){
                return ResponseHelper::jsonResponse(
                    false,
                    'Penerima bantuan sosial tidak ditemukan',
                    null,
                    404
                );
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil Memperbarui penerima bantuan sosial berdasarkan ID',
                new SocialAssistanceRecipientResource($socialAssistanceRecipient),
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
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById(
                $id
            );

            if(!$socialAssistanceRecipient){
                return ResponseHelper::jsonResponse(
                    false,
                    'Penerima bantuan sosial tidak ditemukan',
                    null,
                    404
                );
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->delete(
                $id
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus penerima bantuan sosial berdasarkan ID',
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
