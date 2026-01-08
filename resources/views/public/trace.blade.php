<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trace Origin - {{ $batch->batch_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .timeline { position: relative; padding: 20px 0; list-style: none; }
        .timeline:before { content: ""; position: absolute; top: 0; bottom: 0; left: 40px; width: 2px; background: #e9ecef; }
        .timeline-item { position: relative; margin-bottom: 30px; padding-left: 70px; }
        .timeline-icon { position: absolute; left: 25px; width: 32px; height: 32px; border-radius: 50%; background: #fff; border: 2px solid #28a745; display: flex; align-items: center; justify-content: center; z-index: 1; }
        .status-badge { position: absolute; top: 20px; right: 20px; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body text-center">
            <h5 class="text-success fw-bold"><i class="bi bi-patch-check-fill"></i> Verified Traceable Product</h5>
            <h3 class="fw-bold mb-0 text-uppercase">{{ $batch->product->name }}</h3>
            <p class="text-muted small">Batch: {{ $batch->batch_no }} | Grade: {{ $batch->quality_grade }}</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-bold mb-4 border-bottom pb-2">Product Journey</h6>
            <ul class="timeline">
                <li class="timeline-item">
                    <span class="timeline-icon"><i class="bi bi-seedling text-success"></i></span>
                    <h6 class="fw-bold">Plantation Started</h6>
                    <p class="small text-muted mb-0">Farmer: {{ $batch->farmer->name }}</p>
                    <p class="small text-muted">Sowing Date: {{ date('d M, Y', strtotime($batch->sowing_date)) }}</p>
                </li>

                <li class="timeline-item">
                    <span class="timeline-icon"><i class="bi bi-shield-shaded text-primary"></i></span>
                    <h6 class="fw-bold">Safety & Health Analysis</h6>
                    <p class="small mb-1">Pesticide Safety:
                        @if($batch->qc_status == 'approved')
                            <span class="text-success fw-bold">Passed</span>
                        @else
                            <span class="text-danger fw-bold">Alert</span>
                        @endif
                    </p>
                    <p class="small text-muted">Moisture Level: {{ $batch->moisture_level }}</p>
                </li>

                <li class="timeline-item">
                    <span class="timeline-icon"><i class="bi bi-box-seam text-warning"></i></span>
                    <h6 class="fw-bold">Harvesting & Packaging</h6>
                    <p class="small text-muted mb-0">Harvest Date: {{ date('d M, Y', strtotime($batch->harvest_date)) }}</p>
                    <p class="small text-muted">Location: {{ $batch->current_location }}</p>
                </li>

                <li class="timeline-item">
                    <span class="timeline-icon"><i class="bi bi-shop text-info"></i></span>
                    <h6 class="fw-bold">Ready for Consumer</h6>
                    <p class="small text-muted mb-0">Exp: {{ date('d M, Y', strtotime($batch->expiry_date)) }}</p>
                    <span class="badge bg-success">Quality Certified</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="mt-3">
        <a href="https://www.google.com/maps?q={{ $batch->latitude }},{{ $batch->longitude }}" class="btn btn-outline-dark w-100 py-2 shadow-sm">
            <i class="bi bi-geo-alt"></i> View Production Farm on Map
        </a>
    </div>

    <footer class="text-center mt-4 pb-4">
        <p class="text-muted" style="font-size: 10px;">Powered by Smart Traceability System © 2026</p>
    </footer>
</div>

</body>
</html>
