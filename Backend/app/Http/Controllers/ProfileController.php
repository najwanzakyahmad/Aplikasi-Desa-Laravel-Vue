<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $profile = $this->profileRepository->getProfile();

            if(!$profile){
                return ResponseHelper::jsonResponse(
                    false,
                    'Profile tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan profile',
                new ProfileResource($profile),
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
    public function store(ProfileStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $profiles = $this->profileRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan profile',
                new ProfileResource($profiles),
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
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $profile = $this->profileRepository->update($data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui profile',
                new ProfileResource($profile),
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
