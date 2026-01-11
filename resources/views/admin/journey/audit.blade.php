@extends('admin.layout.erp.app')
@section('content')
    <div class="container-fluid px-3">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div>
                <h2 class="text-dark fw-bold mb-1"><i class="ri-pie-chart-2-line text-success me-2"></i>Financial Audit Report</h2>
                <p class="text-muted small mb-0">Detailed cost analysis and profit distribution across supply chain batches.</p>
            </div>
            <button class="btn btn-outline-primary rounded-pill shadow-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Download Report
            </button>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-soft-primary p-3 rounded-3">
                                <i class="bi bi-wallet2 fs-4 text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-0 small fw-bold">TOTAL INVESTMENT</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($batchAudits->sum('total_buying') + $batchAudits->sum('total_extra_cost'), 0) }} ৳</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-soft-success p-3 rounded-3">
                                <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-0 small fw-bold">TOTAL REVENUE</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($batchAudits->sum('total_revenue'), 0) }} ৳</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-soft-warning p-3 rounded-3">
                                <i class="bi bi-piggy-bank fs-4 text-warning"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-0 small fw-bold">NET PROFIT</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($batchAudits->sum('total_profit'), 0) }} ৳</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-soft-info p-3 rounded-3">
                                <i class="bi bi-percent fs-4 text-info"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-0 small fw-bold">AVG MARGIN</p>
                                <h4 class="mb-0 fw-bold">
                                    {{ $batchAudits->count() > 0 ? round(($batchAudits->sum('total_profit') / ($batchAudits->sum('total_buying') + 1)) * 100, 1) : 0 }}%
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Audit Table --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold text-dark">Batch-wise Performance Breakdown</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small uppercase">
                            <th class="ps-4 border-0">Batch & Product Details</th>
                            <th class="border-0">Investment Breakdown</th>
                            <th class="border-0 text-center">Efficiency (ROI)</th>
                            <th class="border-0">Financial Status</th>
                            <th class="text-center pe-4 border-0">Audit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batchAudits as $audit)
                            @php
                                $invest = $audit->total_buying + $audit->total_extra_cost;
                                $margin = $invest > 0 ? ($audit->total_profit / $invest) * 100 : 0;
                                $statusColor = $margin > 20 ? 'success' : ($margin > 10 ? 'primary' : 'warning');
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle p-2 text-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-box-seam text-secondary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $audit->batch->batch_no ?? 'BATCH-N/A' }}</h6>
                                            <small class="text-muted">{{ $audit->product->name ?? 'Unknown Product' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-muted">Buying:</span> <span class="fw-medium">{{ number_format($audit->total_buying, 0) }}</span><br>
                                        <span class="text-muted">Logistics:</span> <span class="fw-medium">{{ number_format($audit->total_extra_cost, 0) }}</span>
                                    </div>
                                </td>
                                <td style="width: 220px;">
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span class="text-muted">ROI Margin</span>
                                        <span class="fw-bold text-{{ $statusColor }}">{{ round($margin, 1) }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $statusColor }}" style="width: {{ $margin }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted">Revenue: <span class="text-dark fw-bold">{{ number_format($audit->total_revenue, 0) }} ৳</span></div>
                                    <div class="small text-muted">Net Profit: <span class="text-success fw-bold">{{ number_format($audit->total_profit, 0) }} ৳</span></div>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ URL('admin/journey?search=' . ($audit->batch->batch_no ?? '')) }}" class="btn btn-sm btn-light border-0 rounded-circle p-2" title="Trace Journey">
                                        <i class="bi bi-arrow-right-short fs-5 text-primary"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5">No Audit Records Found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $batchAudits->links() }}
        </div>
    </div>

    <style>
        .bg-soft-primary { background-color: #eef2ff; }
        .bg-soft-success { background-color: #ecfdf5; }
        .bg-soft-warning { background-color: #fffbeb; }
        .bg-soft-info { background-color: #f0f9ff; }
        .card { transition: all 0.2s ease; }
        .card:hover { transform: translateY(-3px); }
    </style>
@endsection
