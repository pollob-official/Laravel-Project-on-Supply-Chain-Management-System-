<?php

namespace App\Services;

use App\Models\Batch;

class BatchRiskService
{
    public function calculateRiskScore(Batch $batch): array
    {
        $score = 0;
        $factors = [];

        if ($batch->qc_status === 'rejected') {
            $score += 40;
            $factors[] = 'QC rejected';
        } elseif ($batch->qc_status === 'pending') {
            $score += 15;
            $factors[] = 'QC pending';
        }

        if ($batch->residue_risk_level === 'high') {
            $score += 30;
            $factors[] = 'High residue risk';
        } elseif ($batch->residue_risk_level === 'medium') {
            $score += 15;
            $factors[] = 'Medium residue risk';
        }

        if ($batch->safety_score !== null && $batch->safety_score < 70) {
            $score += 20;
            $factors[] = 'Low safety score';
        }

        if ($batch->expiry_date && $batch->expiry_date->isPast()) {
            $score += 30;
            $factors[] = 'Expired product';
        }

        $level = 'low';
        if ($score >= 60) {
            $level = 'high';
        } elseif ($score >= 30) {
            $level = 'medium';
        }

        return [
            'score' => $score,
            'level' => $level,
            'factors' => $factors,
        ];
    }
}

