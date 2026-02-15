<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Show batch details by batch number.
     */
    public function show(string $batch_no)
    {
        $batch = Batch::with(['product.unit', 'source'])->where('batch_no', $batch_no)->first();
        if (!$batch) {
            return response()->json([
                'message' => 'Batch not found',
                'batch_no' => $batch_no,
            ], 404);
        }

        return response()->json([
            'batch_no' => $batch->batch_no,
            'product' => [
                'id' => $batch->product->id ?? null,
                'name' => $batch->product->name ?? null,
                'unit' => $batch->product->unit->short_name ?? null,
            ],
            'source' => [
                'id' => $batch->source->id ?? null,
                'name' => $batch->source->name ?? null,
                'role' => $batch->source->role ?? null,
            ],
            'qc_status' => $batch->qc_status,
            'safety_score' => $batch->safety_score,
            'quality_grade' => $batch->quality_grade,
            'manufacturing_date' => optional($batch->manufacturing_date)->toDateString(),
            'harvest_date' => optional($batch->harvest_date)->toDateString(),
            'target_retail_price' => $batch->target_retail_price,
            'currency' => $batch->currency,
            'qr_code' => $batch->qr_code,
            'current_location' => $batch->current_location,
            'remarks' => $batch->qc_remarks,
            'updated_at' => $batch->updated_at?->toDateTimeString(),
        ], 200);
    }
}

