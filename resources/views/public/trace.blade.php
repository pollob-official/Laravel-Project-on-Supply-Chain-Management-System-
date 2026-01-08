<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Traceability | {{ $batch->batch_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .trace-card { border-radius: 15px; border: none; overflow: hidden; }
        .safety-header { background: linear-gradient(45deg, #198754, #20c997); color: white; padding: 20px; text-align: center; }
        .timeline { position: relative; padding: 20px 0; list-style: none; }
        .timeline:before { content: ''; position: absolute; top: 0; bottom: 0; width: 3px; background: #e9ecef; left: 31px; }
        .timeline-item { position: relative; margin-bottom: 30px; padding-left: 60px; }
        .timeline-icon { position: absolute; left: 15px; width: 35px; height: 35px; background: white; border: 3px solid #198754; border-radius: 50%; z-index: 1; display: flex; align-items: center; justify-content: center; color: #198754; }
        .score-meter { font-size: 2.5rem; font-weight: bold; color: white; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card trace-card shadow-lg">

                {{-- ১. অটোমেটিক সেফটি স্কোর লজিক --}}
                @php
                    $safety_score = 95; // ডিফল্ট স্কোর
                    if($batch->last_pesticide_date && $batch->harvest_date){
                        $days = \Carbon\Carbon::parse($batch->last_pesticide_date)->diffInDays($batch->harvest_date);
                        if($days < 14) $safety_score = 70; // বিপদজনক হলে লাল সংকেত
                    }
                @endphp

                <div class="safety-header" style="{{ $safety_score < 80 ? 'background: linear-gradient(45deg, #dc3545, #ffc107);' : '' }}">
                    <div class="score-meter">{{ $safety_score }}%</div>
                    <div class="fw-bold text-uppercase">Safety & Trust Score</div>
                    <small><i class="bi bi-patch-check"></i> Verified by SmartAgri Ecosystem</small>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-0">{{ $batch->product->name ?? 'N/A' }}</h4>
                        <span class="badge bg-light text-dark border small">Batch: {{ $batch->batch_no }}</span>
                    </div>

                    {{-- ২. জার্নি টাইমলাইন --}}
                    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-map"></i> Production Journey</h6>
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-icon"><i class="bi bi-seedling"></i></div>
                            <div class="fw-bold small">Sowing (বপন)</div>
                            <div class="text-muted small">{{ $batch->sowing_date ? date('M d, Y', strtotime($batch->sowing_date)) : 'N/A' }}</div>
                            <small class="text-info">Variety: {{ $batch->seed_variety }}</small>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon"><i class="bi bi-droplet-half"></i></div>
                            <div class="fw-bold small">Last Pesticide</div>
                            <div class="text-muted small">{{ $batch->last_pesticide_date ? date('M d, Y', strtotime($batch->last_pesticide_date)) : 'No Chemical Used' }}</div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-icon"><i class="bi bi-scissor"></i></div>
                            <div class="fw-bold small">Harvest (সংগ্রহ)</div>
                            <div class="text-muted small">{{ $batch->harvest_date ? date('M d, Y', strtotime($batch->harvest_date)) : 'N/A' }}</div>
                        </li>
                    </ul>

                    {{-- ৩. জিপিএস ম্যাপ বাটন --}}
                    @if($batch->latitude && $batch->longitude)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $batch->latitude }},{{ $batch->longitude }}" target="_blank" class="btn btn-dark w-100 rounded-pill mb-3 shadow">
                        <i class="bi bi-geo-alt"></i> View Farm on Google Maps
                    </a>
                    @endif

                    <div class="alert {{ $safety_score < 80 ? 'alert-danger' : 'alert-success' }} py-2 text-center small">
                        <i class="bi bi-calendar-check"></i> Quality Grade: <strong>Grade {{ $batch->quality_grade ?? 'A' }}</strong>
                    </div>
                </div>

                <div class="card-footer bg-white text-center py-3 border-0">
                    <small class="text-muted">Safe Food for a Better Life</small>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
