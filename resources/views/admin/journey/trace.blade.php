@extends("admin.layout.erp.app")
@section("content")
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(45deg, #1a1a1a, #2d5a27);">
                    <div>
                        <h4 class="fw-bold mb-0">Product Authenticity Report</h4>
                        <small class="opacity-75">Tracking ID: <span class="text-warning">{{ $tracking_no }}</span></small>
                    </div>
                    <div class="text-end d-none d-sm-block">
                        <i class="bi bi-patch-check-fill fs-2 text-success"></i>
                    </div>
                </div>

                <div class="card-body p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-white rounded shadow-sm border-start border-4 border-success">
                        <h5 class="mb-0 text-dark"><strong>Item:</strong> {{ $history->first()->product->name }}</h5>
                        <span class="small text-muted text-uppercase">Certified Journey</span>
                    </div>

                    <div class="position-relative">
                        <div class="position-absolute start-0 h-100 border-start border-2 border-success opacity-25 ms-2"></div>

                        @foreach($history as $index => $step)
                        <div class="mb-3 position-relative ps-4">
                            <div class="position-absolute start-0 translate-middle-x bg-success rounded-circle ms-2 shadow-sm"
                                 style="width: 14px; height: 14px; z-index: 10; border: 3px solid #fff; margin-top: 8px;"></div>

                            <div class="card border shadow-sm rounded-3">
                                <div class="card-body p-2">
                                    <div class="row align-items-center g-2">
                                        <div class="col-md-5 border-end-md">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success-subtle text-success rounded p-2 me-2">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div>
                                                    <span class="badge bg-success text-uppercase p-1 mb-1" style="font-size: 9px;">{{ $step->current_stage }}</span>
                                                    <h6 class="mb-0 fw-bold" style="font-size: 14px;">{{ $step->seller->name ?? 'Producer' }}</h6>
                                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $step->seller->address ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="row text-center g-1 mb-1">
                                                <div class="col-4">
                                                    <small class="text-muted d-block" style="font-size: 10px;">Buy</small>
                                                    <span class="fw-bold small">{{ number_format($step->buying_price, 2) }} ৳</span>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block" style="font-size: 10px;">Profit/Cost</small>
                                                    <span class="text-primary fw-bold small">+{{ number_format($step->profit_margin + $step->extra_cost, 2) }} ৳</span>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block" style="font-size: 10px;">Handover</small>
                                                    <span class="text-dark fw-bold small">{{ number_format($step->selling_price, 2) }} ৳</span>
                                                </div>
                                            </div>
                                            <div class="progress shadow-sm" style="height: 5px;">
                                                @php
                                                    $total_p = $step->selling_price > 0 ? $step->selling_price : 1;
                                                    $buy_percent = ($step->buying_price / $total_p) * 100;
                                                    $profit_percent = (($step->profit_margin + $step->extra_cost) / $total_p) * 100;
                                                @endphp
                                                <div class="progress-bar bg-secondary opacity-50" style="width: {{ $buy_percent }}%"></div>
                                                <div class="progress-bar bg-success" style="width: {{ $profit_percent }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1" style="font-size: 10px;">
                                                <span class="text-muted">Date: {{ $step->created_at->format('d/m/y') }}</span>
                                                <span class="text-muted">To: <strong>{{ $step->buyer->name ?? 'N/A' }}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-white text-center py-2">
                    <p class="small text-muted mb-0" style="font-size: 11px;">
                        <i class="bi bi-shield-lock-fill me-1"></i> Verified by SupplyChain ERP System. This document is a digital proof of origin.
                    </p>
                </div>
            </div>

            <div class="text-center mt-3 no-print">
                <button class="btn btn-dark btn-sm rounded-pill px-4" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Printing Standard */
    @media print {
        .no-print, .navbar, .footer { display: none !important; }
        body { background: white !important; margin: 0; padding: 0; }
        .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; }
        .card-header { -webkit-print-color-adjust: exact; background-color: #1a1a1a !important; color: white !important; }
        .bg-light { background-color: white !important; }
        .badge { border: 1px solid #ccc !important; color: black !important; -webkit-print-color-adjust: exact; }
    }

    /* Responsive border for desktop, hidden in mobile */
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px solid #eee; }
    }
</style>
@endsection
