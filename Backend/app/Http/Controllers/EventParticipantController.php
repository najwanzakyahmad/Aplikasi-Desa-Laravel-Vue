<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller implements HasMiddleware
{
    private EventParticipantRepositoryInterface $eventParticipantReposity;

    public function __construct(EventParticipantRepositoryInterface $eventParticipantReposity) {
        $this->eventParticipantReposity = $eventParticipantReposity;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using([
                'event-participant-list|event-participant-menu|event-participant-create|event-participant-edit|event-participant-delete'
            ]), only: ['index', 'getAllPaginated', 'show']),

            new Middleware(PermissionMiddleware::using(['event-participant-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-participant-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-participant-delete']), only: ['destroy'])
            ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $events = $this->eventParticipantReposity->getAll(
                $request->search,
                $request->limit,
                true
            );

            // Hitung total dengan aman untuk 3 kemungkinan tipe:
            if (method_exists($events, 'total')) {
                // LengthAwarePaginator / Paginator
                $total = $events->total();           // total semua data (bukan hanya per halaman)
                $pageCount = $events->count();       // item di halaman saat ini (opsional)
            } elseif (method_exists($events, 'count')) {
                // Collection
                $total = $events->count();
            } else {
                // Fallback ke count() PHP
                $total = count($events);
            }

            return ResponseHelper::jsonResponse(
                true,
                "Berhasil mendapatkan seluruh data partisipan event. Total data: {$total}",
                EventParticipantResource::collection($events),
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

            $events = $this->eventParticipantReposity->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data partisipan event.',
                PaginateResource::make($events, EventParticipantResource::class),
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
    public function store(EventParticipantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $events = $this->eventParticipantReposity->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan partisipan event',
                new EventParticipantResource($events),
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
            $event = $this->eventParticipantReposity->getById(
                $id
            );

            if(!$event){
                return ResponseHelper::jsonResponse(
                    false,
                    'Partisipan event tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan partisipan event berdasarkan ID',
                new EventParticipantResource($event),
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
    public function update(EventParticipantUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $event = $this->eventParticipantReposity->getById(
                $id
            );

            if(!$event){
                return ResponseHelper::jsonResponse(
                    false,
                    'Partisipan event tidak ditemukan',
                    null,
                    404
                );
            }

            $event = $this->eventParticipantReposity->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui partisipan event',
                new EventParticipantResource($event),
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
            $event = $this->eventParticipantReposity->getById($id);

            if(!$event){
                return ResponseHelper::jsonResponse(
                    false,
                    'Partisipan event tidak ditemukan',
                    null,
                    404
                );
            }

            $event = $this->eventParticipantReposity->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus partisipan event',
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
