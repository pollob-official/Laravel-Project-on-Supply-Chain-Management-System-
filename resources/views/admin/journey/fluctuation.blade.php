@extends('admin.layout.erp.app')
@section('content')
<div class="container-fluid px-3">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <div>
            <h2 class="text-dark fw-bold mb-1">
                <i class="ri-bar-chart-2-line text-primary me-2"></i>Price Fluctuation
            </h2>
            <p class="text-muted mb-0">Track average selling price trend over time.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body">
            <form action="{{ URL('admin/journey/fluctuation') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filter by Product</label>
                    <select name="product_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $selectedProductId == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ri-filter-3-line me-1"></i>Apply Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            <h5 class="header-title mb-3">Average Selling Price Trend</h5>
            <div id="price-fluctuation-chart" class="apex-charts" style="min-height: 320px;"></div>
            @if(empty($chartLabels))
                <p class="text-muted small mt-3 mb-0">No journey data available yet to plot price fluctuations.</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var labels = JSON.parse('{!! json_encode($chartLabels) !!}');
        var series = JSON.parse('{!! json_encode($chartSeries) !!}');

        var options = {
            chart: {
                type: 'line',
                height: 320,
                toolbar: { show: false }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            dataLabels: {
                enabled: false
            },
            series: series,
            xaxis: {
                categories: labels,
                labels: {
                    style: {
                        colors: '#6b7280'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return '৳' + val;
                    },
                    style: {
                        colors: '#6b7280'
                    }
                }
            },
            colors: ['#10b981'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return '৳' + val.toFixed(2);
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb'
            }
        };

        var chart = new ApexCharts(document.querySelector("#price-fluctuation-chart"), options);
        chart.render();
    });
</script>
@endsection
