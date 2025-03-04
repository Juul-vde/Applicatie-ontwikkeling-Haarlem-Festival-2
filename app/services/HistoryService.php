<?php
namespace App\Services;

use App\Models\User;
use App\Enums\Role;
use App\Repositories\UserRepository;

class HistoryService
{
    private $historyRepository;

    public function __construct()
    {
        $this->historyRepository = new HistoryRepository();
    }

    public function getHistoryLocation(int $id): ?HistoryLocation
    {
        return $this->historyRepository->getLocationById($id);
    }

    public function getAllHistoryLocations(): array
    {
        return $this->historyRepository->getAllLocations();
    }
}