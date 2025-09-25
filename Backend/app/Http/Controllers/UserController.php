<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\PaginateResource;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($users, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $users->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $users->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($users, 'count')) {
                // Collection
                $total = $users->count();
            } else {
                // Fallback ke count() PHP
                $total = count($users);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Success Get All Data Users. Total data: {$total}",
                UserResource::collection($users),
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

        $users = $this->userRepository->getAllPaginated(
            $request['search'] ?? null,
            $request['row_per_page']
        );

        return ResponseHelper::jsonResponse(
            true,
            'Success Get All Data Users',
            PaginateResource::make($users, UserResource::class),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $user = $this->userRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'User Berhasil ditambahkan',
                new UserResource($user),
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
