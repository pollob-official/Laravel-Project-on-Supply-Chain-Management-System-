<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustTrace | {{ $batch->batch_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #059669;
            --accent: #0284c7;
            --bg: #f1f5f9;
        }

        body {
            background: var(--bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        .main-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .hero-section {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .price-tag {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .stepper {
            position: relative;
            padding-left: 50px;
        }

        .stepper::before {
            content: '';
            position: absolute;
            left: 24px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: #e2e8f0;
            border-left: 2px dashed #cbd5e1;
        }

        .step-item {
            position: relative;
            margin-bottom: 40px;
        }

        .step-dot {
            position: absolute;
            left: -38px;
            width: 30px;
            height: 30px;
            background: white;
            border: 3px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            color: var(--primary);
            font-size: 0.9rem;
        }

        .stakeholder-card {
            background: #f8fafc;
            border-radius: 15px;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #e2e8f0;
        }

        .financial-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .map-preview {
            height: 150px;
            background: #e2e8f0;
            border-radius: 15px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            position: relative;
            background-image: url('https://api.mapbox.com/styles/v1/mapbox/light-v10/static/pin-s+ff0000({{ $batch->longitude }},{{ $batch->latitude }})/{{ $batch->longitude }},{{ $batch->latitude }},10/400x150?access_token=YOUR_MAPBOX_TOKEN');
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="glass-card">

            <div class="hero-section">
                <div class="mb-2">
                    <span class="price-tag">
                        <i class="bi bi-tag"></i> MRP: ৳{{ number_format($batch->target_retail_price, 2) }}
                    </span>
                </div>
                <h1 class="display-4 fw-bold mb-0">{{ $batch->safety_score }}%</h1>
                <p class="text-uppercase small fw-semibold">Safety & Trust Score</p>
                <div class="badge bg-success rounded-pill px-3 py-2">
                    <i class="bi bi-patch-check-fill me-1"></i> Verified Traceability
                </div>
            </div>

            <div class="p-4">
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-1">{{ $batch->product->name ?? 'Premium Product' }}</h2>
                    <p class="text-muted small">Batch: {{ $batch->batch_no }} | Grade: {{ $batch->quality_grade }}</p>
                </div>

                <div class="financial-card">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cash-coin text-primary me-2"></i>Price Breakdown
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Farmer Received:</span>
                        <span class="fw-bold small">৳{{ number_format($batch->farmer_price, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Processing & Logistics:</span>
                        <span class="fw-bold small">৳{{ number_format($batch->processing_cost, 2) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success">Retail Price (MRP):</span>
                        <span class="badge bg-success fs-6">৳{{ number_format($batch->target_retail_price, 2) }}</span>
                    </div>
                </div>

                <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-diagram-3-fill me-2 text-primary"></i> Farm-to-Fork
                    Journey</h5>

                <div class="stepper">
                    <div class="step-item">
                        <div class="step-dot"><i class="bi bi-house-door-fill"></i></div>
                        <div class="fw-bold text-primary text-uppercase" style="font-size: 0.75rem;">Stage 1: Origin
                        </div>
                        <h6 class="fw-bold mb-1">{{ $batch->farmer->name ?? 'Contracted Farmer' }}</h6>
                        <p class="text-muted small mb-2"><i class="bi bi-calendar-event"></i> Harvested:
                            {{ date('M d, Y', strtotime($batch->harvest_date)) }}</p>

                        <div class="stakeholder-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold">Field Location</span>
                                <span class="badge bg-info-subtle text-info">Verified GPS</span>
                            </div>
                            @if ($batch->latitude && $batch->longitude)
                                <a href="https://www.google.com/maps?q={{ $batch->latitude }},{{ $batch->longitude }}"
                                    target="_blank" class="text-decoration-none">
                                    <div class="map-preview">
                                        <span class="badge bg-dark rounded-pill shadow-sm"><i
                                                class="bi bi-geo-alt-fill text-danger"></i> View Farm on Map</span>
                                    </div>
                                </a>
                            @endif
                            <div class="mt-2 small text-muted">
                                Pesticide Safety:
                                <strong>{{ $batch->qc_status == 'approved' ? 'Passed' : 'Pending' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-dot"><i class="bi bi-shield-check"></i></div>
                        <div class="fw-bold text-primary text-uppercase" style="font-size: 0.75rem;">Stage 2: Quality
                            Audit</div>
                        <h6 class="fw-bold mb-1">QC Certified by Sagri Lab</h6>
                        <p class="text-muted small mb-2"><i class="bi bi-calendar-check"></i> Tested:
                            {{ date('M d, Y', strtotime($batch->updated_at)) }}</p>
                        <div class="stakeholder-card bg-success-subtle border-0">
                            <div class="small fw-bold text-success mb-1">
                                <i class="bi bi-droplet-half"></i> Moisture: {{ $batch->moisture_level }}%
                            </div>
                            <p class="mb-0 text-dark small">{{ $batch->qc_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-light rounded-4 text-center border">
                    <h6 class="fw-bold small mb-1">Our Transparency Promise</h6>
                    <p class="text-muted mb-0" style="font-size: 0.7rem;">
                        Every unit you buy supports sustainable farming.
                        @if ($batch->target_retail_price > 0)
                            We ensure
                            <strong>{{ number_format(($batch->farmer_price / $batch->target_retail_price) * 100, 1) }}%</strong>
                            of the retail price goes directly to the farmer.
                        @else
                            We ensure fair wages and sustainable growth for our local farmers.
                        @endif
                    </p>
                </div>
            </div>

            <div class="p-4 text-center border-top">
                <button class="btn btn-dark w-100 rounded-pill mb-3 py-3 fw-bold shadow">
                    <i class="bi bi-download me-2"></i> Download Trace Certificate
                </button>
                <p class="small text-muted mb-0">SmartAgri ERP - ID: {{ $batch->batch_no }}</p>
            </div>
        </div>
    </div>

</body>

</html>
