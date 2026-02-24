<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Repositories\InformationRepository;
use App\Services\InformationService;
use App\Http\Requests\Admin\Information\StoreInformationRequest;
use App\Http\Requests\Admin\Information\UpdateInformationRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InformationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected InformationRepository $infoRepo,
        protected InformationService $infoService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'type', 'is_active']);
        $informations = $this->infoRepo->getFilteredList($filters);

        
        return $this->successResponse('Daftar Informasi', $informations);
    }

    public function store(StoreInformationRequest $request): JsonResponse
    {
        $info = $this->infoService->createInformation($request->validated());
        return $this->successResponse('Informasi berhasil dibuat', $info, 201);
    }

    public function show(int $id): JsonResponse
    {
        $info = $this->infoRepo->findById($id);
        return $this->successResponse('Detail Informasi', $info);
    }

    public function update(UpdateInformationRequest $request, int $id): JsonResponse
    {
        $info = $this->infoService->updateInformation($id, $request->validated());
        return $this->successResponse('Informasi berhasil diupdate', $info);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->infoService->deleteInformation($id);
        return $this->successResponse('Informasi berhasil dihapus');
    }

    public function toggleStatus(int $id): JsonResponse
    {
        $info = $this->infoService->toggleStatus($id);
        return $this->successResponse('Status informasi berhasil diubah', $info);
    }
}
