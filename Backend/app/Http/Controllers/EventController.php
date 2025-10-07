<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventController extends Controller implements HasMiddleware
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using([
                'event-list|event-menu|event-create|event-edit|event-delete'
            ]), only: ['index', 'getAllPaginated', 'show']),

            new Middleware(PermissionMiddleware::using(['event-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-delete']), only: ['destroy'])
            ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $events = $this->eventRepository->getAll(
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
                "Berhasil mendapatkan seluruh data event. Total data: {$total}",
                EventResource::collection($events),
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

            $events = $this->eventRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data event.',
                PaginateResource::make($events, EventResource::class),
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
    public function store(EventStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $events = $this->eventRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan event',
                new EventResource($events),
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
            $event = $this->eventRepository->getById(
                $id
            );

            if(!$event){
                return ResponseHelper::jsonResponse(
                    false,
                    'Event tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan event berdasarkan ID',
                new EventResource($event),
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
    public function update(EventUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $event = $this->eventRepository->getById(
                $id
            );

            if(!$event){
                return ResponseHelper::jsonResponse(
                    false,
                    'Event tidak ditemukan',
                    null,
                    404
                );
            }

            $event = $this->eventRepository->update($id, $data);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui event',
                new EventResource($event),
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
            $event = $this->eventRepository->getById($id);

            if(!$event){
                return ResponseHelper::jsonResponse(
                    false,
                    'Event tidak ditemukan',
                    null,
                    404
                );
            }

            $event = $this->eventRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus event',
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
