<?php

namespace App\Services;

use App\Models\ProductJourney;

class BatchInventoryService
{
    public function getUsedQuantity(int $batchId): float
    {
        return (float) ProductJourney::where('batch_id', $batchId)
            ->sum('quantity_moved');
    }
}

