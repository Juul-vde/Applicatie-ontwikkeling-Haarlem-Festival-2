<?php
namespace App\Controllers;

class HistoryController
{
    private $historyService;

    public function index()
    {
        require __DIR__ . '/../views/history/index.php';
    }

    public function showHistoryLocation(int $id)
    {
        $historyLocation = $this->historyService->getHistoryLocation($id);

        if ($historyLocation) {
            return response()->json($historyLocation);
        } else {
            return response()->json(['message' => 'Location not found'], 404);
        }
    }

    public function showAllHistoryLocations()
    {
        $locations = $this->historyService->getAllHistoryLocations();
        return response()->json($locations);
    }
}