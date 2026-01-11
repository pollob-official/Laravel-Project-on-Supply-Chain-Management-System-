@extends('admin.layout.erp.app')
@section('content')
    <div class="container-fluid px-3">

        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div>
                <h2 class="text-danger fw-bold"><i class="ri-error-warning-line me-2"></i>Price Hike Alerts</h2>
                <p class="text-muted mb-0">Currently showing alerts for margins above <b>{{ $marginLimit }}%</b></p>
            </div>

            {{-- Dynamic Margin Filter --}}
            <form action="{{ URL('admin/journey/price-alerts') }}" method="GET" class="d-flex align-items-end gap-2">
                <div>
                    <label class="small fw-bold text-muted">Set Margin Limit (%)</label>
                    <input type="number" name="margin" value="{{ $marginLimit }}" class="form-control form-control-sm shadow-sm" style="width: 100px;" placeholder="e.g. 15">
                </div>
                <button type="submit" class="btn btn-sm btn-danger px-3">Filter</button>
            </form>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-danger-subtle text-white">
                    <tr>
                        <th class="ps-4">Batch & Product</th>
                        <th>Seller -> Buyer</th>
                        <th>Price Details</th>
                        <th>Margin %</th>
                        <th class="text-center pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alerts as $alert)
                        @php
                            $investment = $alert->buying_price + $alert->extra_cost;
                            $margin_percent = $investment > 0 ? ($alert->profit_margin / $investment) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">{{ $alert->batch->batch_no ?? 'N/A' }}</span><br>
                                <small class="text-muted">{{ $alert->product->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="small fw-medium">
                                    <span class="text-danger">{{ $alert->seller->name ?? 'N/A' }}</span>
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <span class="text-success">{{ $alert->buyer->name ?? 'N/A' }}</span>
                                </div>
                                <span class="badge bg-light text-dark border mt-1" style="font-size: 10px;">{{ $alert->current_stage }}</span>
                            </td>
                            <td>
                                <div class="small text-muted text-nowrap">Investment: {{ number_format($investment, 0) }}</div>
                                <div class="fw-bold text-dark">Sell: {{ number_format($alert->selling_price, 0) }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <h5 class="text-danger fw-bold mb-0">{{ round($margin_percent, 1) }}%</h5>
                                    @if($margin_percent > 50)
                                        <i class="bi bi-exclamation-triangle-fill text-danger shadow-sm" title="Critical Hike"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ URL('admin/journey/edit/'.$alert->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    Check
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No abnormal price hikes found above {{ $marginLimit }}% margin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
