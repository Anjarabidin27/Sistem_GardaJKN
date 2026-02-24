<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Repositories\InformationRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InformationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected InformationRepository $infoRepo
    ) {}

    public function index(): JsonResponse
    {
        $informations = $this->infoRepo->getActiveInformations();
        return $this->successResponse('Daftar Informasi/Pengumuman', $informations);
    }

    public function show(int $id): JsonResponse
    {
        $info = $this->infoRepo->findById($id);
        
        if (!$info->is_active) {
            return $this->errorResponse('Informasi tidak tersedia', 404);
        }

        return $this->successResponse('Detail Informasi', $info);
    }
}
