<?php

namespace App\Services;

use App\Models\Batch;

class DataCompletenessService
{
    public function scoreBatch(Batch $batch): int
    {
        $fields = [
            'field_id',
            'seed_brand',
            'seed_variety',
            'sowing_date',
            'harvest_date',
            'pesticide_history',
            'fertilizer_history',
            'moisture_level',
            'qc_status',
            'quality_grade',
            'current_location',
        ];

        $filled = 0;
        foreach ($fields as $field) {
            if (!is_null($batch->{$field})) {
                $filled++;
            }
        }

        return (int) round(($filled / count($fields)) * 100);
    }
}

