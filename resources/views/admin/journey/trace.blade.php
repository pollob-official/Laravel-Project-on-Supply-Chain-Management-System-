<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trace Integrity | {{ $tracking_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #064e3b;
            --primary-light: #10b981;
            --accent-gold: #fbbf24;
            --bg-slate: #f8fafc;
        }

        body {
            background-color: var(--bg-slate);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        /* Hero Header */
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #065f46 100%);
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            padding: 60px 20px;
            color: white;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .verified-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        /* Main Content Container */
        .main-card {
            margin-top: -50px;
            background: white;
            border-radius: 30px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Product Identity Area */
        .product-brand {
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Timeline / Journey Styling */
        .journey-container {
            padding: 30px 20px;
        }

        .timeline-item {
            position: relative;
            padding-left: 45px;
            margin-bottom: 35px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 30px;
            bottom: -40px;
            width: 2px;
            background: #e2e8f0;
            border-left: 2px dashed #cbd5e1;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 0;
            width: 36px;
            height: 36px;
            background: white;
            border: 4px solid var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            color: var(--primary-dark);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        }

        .journey-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        /* Price Transparency Section */
        .price-glass {
            background: #fdfdfd;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 25px;
            margin: 20px 0;
        }

        .stat-box {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 15px;
            border: 1px solid #f1f5f9;
        }

        /* Print Button Floating */
        .btn-print {
            background: var(--primary-dark);
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            border: none;
            box-shadow: 0 10px 20px rgba(6, 78, 59, 0.2);
            transition: all 0.3s;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            background: #043a2c;
            color: white;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .main-card { box-shadow: none; margin-top: 0; }
            .hero-gradient { -webkit-print-color-adjust: exact; border-radius: 0; }
        }
    </style>
</head>

<body>

    <header class="hero-gradient">
        <div class="verified-badge">
            <i class="bi bi-patch-check-fill text-warning"></i>
            <span class="small fw-bold text-uppercase tracking-wider">Authenticity Guaranteed</span>
        </div>
        <h1 class="display-5 fw-bold mb-1">Product Origin Report</h1>
        <p class="opacity-75">Tracking ID: <span class="fw-bold">{{ $tracking_no }}</span></p>
    </header>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="main-card">

                    <div class="product-brand">
                        <span class="badge bg-success-subtle text-success mb-2 px-3 py-2 rounded-pill">
                            <i class="bi bi-box-seam me-1"></i> Premium Grade
                        </span>
                        <h2 class="fw-bold text-dark">{{ $history->first()->product->name ?? 'Rice Product' }}</h2>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <div class="small text-muted"><i class="bi bi-layers"></i> Batch: <strong>{{ $history->first()->batch->batch_code ?? 'N/A' }}</strong></div>
                            <div class="small text-muted"><i class="bi bi-geo-alt"></i> Origin: <strong>{{ $history->first()->location ?? 'Bangladesh' }}</strong></div>
                        </div>
                    </div>

                    <div class="journey-container">
                        <h5 class="fw-bold mb-4 px-2">
                            <i class="bi bi-distribute-vertical text-success me-2"></i> Farm-to-Table Journey
                        </h5>

                        @foreach($history as $index => $step)
                        <div class="timeline-item">
                            <div class="timeline-dot">
                                @if($step->current_stage == 'Farmer') <i class="bi bi-house-heart"></i>
                                @elseif($step->current_stage == 'Miller') <i class="bi bi-gear-wide-connected"></i>
                                @elseif($step->current_stage == 'Wholesaler') <i class="bi bi-truck"></i>
                                @else <i class="bi bi-shop"></i> @endif
                            </div>
                            <div class="journey-card shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-{{ $step->current_stage == 'Farmer' ? 'success' : 'primary' }} text-uppercase mb-1" style="font-size: 10px;">
                                            {{ $step->current_stage }}
                                        </span>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $step->seller->name ?? 'Authorized Partner' }}</h6>
                                    </div>
                                    <small class="text-muted fw-semibold">{{ $step->created_at->format('M d, Y') }}</small>
                                </div>
                                <p class="small text-muted mb-3"><i class="bi bi-geo-alt"></i> {{ $step->location ?? 'Registered Facility' }}</p>

                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <small class="text-muted d-block">Quality Status</small>
                                            <span class="fw-bold text-success small">{{ $step->quality_status ?? 'Certified' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <small class="text-muted d-block">Price Point</small>
                                            <span class="fw-bold text-dark small">৳{{ number_format($step->selling_price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-light border-top">
                        <h6 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow text-success me-2"></i>Impact Transparency</h6>
                        <div class="price-glass shadow-sm">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Farmer's Fair Share:</span>
                                <span class="fw-bold text-success">৳{{ number_format($history->where('current_stage', 'Farmer')->first()->selling_price ?? 0, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Logistics & Processing:</span>
                                <span class="fw-bold text-dark">৳{{ number_format($history->sum('extra_cost'), 2) }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success shadow-none" style="width: 70%"></div>
                                <div class="progress-bar bg-warning shadow-none" style="width: 30%"></div>
                            </div>
                            <p class="small text-muted mb-0 mt-3" style="font-size: 11px; line-height: 1.5;">
                                <i class="bi bi-info-circle me-1"></i>
                                This transparency report ensures that every stakeholder in the supply chain receives a fair wage, promoting sustainable agriculture in Bangladesh.
                            </p>
                        </div>
                    </div>

                    <div class="p-4 text-center">
                        <div class="mb-4 no-print">
                            <button class="btn btn-print px-5 shadow" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i> Download Trace Certificate
                            </button>
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <img src="https://cdn-icons-png.flaticon.com/512/9131/9131546.png" width="30" alt="Shield">
                            <span class="small fw-bold text-muted">Secured by SmartAgri ERP Blockchain Integrity</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
