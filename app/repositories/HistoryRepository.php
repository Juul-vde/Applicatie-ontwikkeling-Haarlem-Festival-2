<?php

namespace App\Repositories;

use App\Models\HistoryLocation;

class HistoryRepository
{
    public function getLocationById(int $id): ?HistoryLocation
    {
        return HistoryLocation::find($id);
    }

    public function getAllLocations(): array
    {
        return HistoryLocation::all()->toArray();
    }
}
