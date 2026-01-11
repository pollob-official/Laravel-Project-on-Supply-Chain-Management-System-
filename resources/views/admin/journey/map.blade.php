@extends('admin.layout.erp.app')
@section('content')
<div class="container-fluid px-3">
    <div class="mb-4 mt-3">
        <h2 class="text-dark fw-bold"><i class="ri-map-pin-line text-primary me-2"></i>Supply Chain Journey Map</h2>
        <p class="text-muted">Visual representation of product movement from origin to destination.</p>
    </div>

    {{-- Search Bar --}}
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body">
            <form action="{{ URL('admin/journey/map') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Enter Batch No or Tracking ID (e.g. BATCH-101)">
                <button type="submit" class="btn btn-primary px-4">Trace Map</button>
            </form>
        </div>
    </div>

    @if(count($journeys) > 0)
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="timeline">
                @foreach($journeys as $index => $step)
                <div class="timeline-item d-flex mb-5">
                    <div class="timeline-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="min-width: 50px; height: 50px; z-index: 1;">
                        <i class="bi {{ $index == 0 ? 'bi-house-heart' : ($index == count($journeys)-1 ? 'bi-shop' : 'bi-truck') }}"></i>
                    </div>
                    <div class="timeline-content ms-4 p-4 bg-white shadow-sm rounded-4 border-start border-4 border-primary w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold text-dark mb-0">{{ $step->current_stage }} Stage</h5>
                            <span class="badge bg-light text-primary border">{{ $step->created_at->format('d M, Y | h:i A') }}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small uppercase fw-bold">From (Seller)</p>
                                <h6 class="text-danger fw-bold">{{ $step->seller->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small uppercase fw-bold">To (Buyer)</p>
                                <h6 class="text-success fw-bold">{{ $step->buyer->name ?? 'N/A' }}</h6>
                            </div>
                        </div>

                        <hr class="my-3 opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small">
                                <i class="bi bi-geo-alt-fill text-muted me-1"></i> {{ $step->location ?? 'Location N/A' }}
                            </div>
                            <div class="fw-bold text-dark">
                                Price: {{ number_format($step->selling_price, 0) }} ৳
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @elseif($search)
        <div class="text-center py-5">
            <i class="bi bi-search fs-1 text-muted"></i>
            <p class="mt-2 text-muted">No journey map found for "{{ $search }}"</p>
        </div>
    @endif
</div>

<style>
    .timeline { position: relative; }
    .timeline::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 10px;
        bottom: 10px;
        width: 3px;
        background: #dee2e6;
    }
    .timeline-item { position: relative; }
    .bg-soft-primary { background-color: #eef2ff; }
</style>
@endsection
