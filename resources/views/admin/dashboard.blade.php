@extends('admin.layout.erp.app')

@section('content')
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ERP</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Supply Chain Overview</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-primary">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-qr-code-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">Active Batches</h6>
                        <h2 class="my-2">{{ $total_batches }}</h2>
                        <p class="mb-0">
                            <span class="badge bg-white bg-opacity-10 me-1">Live</span>
                            <span class="text-nowrap">Currently processing</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-info">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-money-dollar-circle-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Revenue">Total Revenue</h6>
                        <h2 class="my-2">{{ number_format($total_revenue, 0) }} ৳</h2>
                        <p class="mb-0">
                            <span class="badge bg-white bg-opacity-10 me-1">Net</span>
                            <span class="text-nowrap">Total sales value</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-success">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-line-chart-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Average Revenue">Net Profit</h6>
                        <h2 class="my-2">{{ number_format($total_profit, 0) }} ৳</h2>
                        <p class="mb-0">
                            <span class="badge bg-white bg-opacity-10 me-1">Earnings</span>
                            <span class="text-nowrap">After expenses</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-sm-6">
                <div class="card widget-flat text-bg-danger">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-group-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Growth">Stakeholders</h6>
                        <h2 class="my-2">{{ $total_stakeholders }}</h2>
                        <p class="mb-0">
                            <span class="badge bg-white bg-opacity-10 me-1">Partners</span>
                            <span class="text-nowrap">Active in system</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Profit Trend (Last 7 Days)</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div style="height: 342px;">
                            <canvas id="profitChart" class="mt-3"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">System Short-cuts</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ URL('admin/batches/create') }}" class="btn btn-soft-primary btn-sm text-start py-2">
                                <i class="ri-add-circle-line me-1"></i> Initiate New Batch
                            </a>
                            <a href="{{ URL('admin/journey') }}" class="btn btn-soft-success btn-sm text-start py-2">
                                <i class="ri-route-line me-1"></i> Update Product Stage
                            </a>
                            <a href="{{ URL('admin/journey/price-alerts') }}" class="btn btn-soft-danger btn-sm text-start py-2">
                                <i class="ri-error-warning-line me-1"></i> Check Price Alerts
                            </a>
                        </div>

                        <div class="mt-4 p-3 border-dashed border-2 text-center rounded">
                            <p class="text-muted font-13 mb-0">Looking for more reports? Check our sidebar for detailed analysis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> </div> {{-- Custom Style for Velonic Look --}}
<style>
    .widget-flat { position: relative; overflow: hidden; }
    .widget-icon {
        font-size: 38px;
        background-color: rgba(255,255,255,0.2);
        height: 48px;
        width: 48px;
        text-align: center;
        line-height: 48px;
        border-radius: 3px;
        display: inline-block;
    }
    .card { border: none; box-shadow: 0 0 35px 0 rgba(154,161,171,.15); margin-bottom: 24px; }
    .header-title { text-transform: uppercase; letter-spacing: 0.02em; font-size: 0.9rem; margin-top: 0; }
    .btn-soft-primary { background-color: rgba(59, 113, 202, 0.1); border-color: transparent; color: #3b71ca; }
    .btn-soft-success { background-color: rgba(20, 164, 77, 0.1); border-color: transparent; color: #14a44d; }
    .btn-soft-danger { background-color: rgba(220, 76, 100, 0.1); border-color: transparent; color: #dc4c64; }
</style>

{{-- Chart Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('profitChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_data->pluck('date')) !!},
            datasets: [{
                label: 'Daily Profit (৳)',
                data: {!! json_encode($chart_data->pluck('profit')) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointHoverRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
