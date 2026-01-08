@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-primary"><i class="bi bi-pencil-square"></i> Edit Product Batch: {{ $batch->batch_no }}</h3>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Batch List
        </a>
    </div>

    <form action="{{ URL('admin/batches/update', $batch->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        {{-- Section 1: Identification --}}
        <h4 class="text-muted mb-3 border-bottom pb-2">Batch Identification & Farmer Info</h4>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $batch->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Source Farmer <span class="text-danger">*</span></label>
                <select name="initial_farmer_id" class="form-select form-control" required>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}" {{ $batch->initial_farmer_id == $farmer->id ? 'selected' : '' }}>
                            {{ $farmer->name }} ({{ $farmer->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Current Stage/Location</label>
                <input type="text" name="current_location" value="{{ $batch->current_location }}" class="form-control" placeholder="e.g. Processing Center">
            </div>
        </div>

        {{-- Section 2: Traceability (Smart Info) --}}
        <h4 class="text-info mb-3 border-bottom pb-2"><i class="bi bi-seedling"></i> Seed & Cultivation Info</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold">Seed Brand</label>
                <input type="text" name="seed_brand" value="{{ $batch->seed_brand }}" class="form-control border-info">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold">Seed Variety</label>
                <input type="text" name="seed_variety" value="{{ $batch->seed_variety }}" class="form-control border-info">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger">Sowing Date</label>
                <input type="date" name="sowing_date" value="{{ $batch->sowing_date }}" class="form-control border-danger">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger">Last Pesticide Date</label>
                <input type="date" name="last_pesticide_date" value="{{ $batch->last_pesticide_date }}" class="form-control border-danger">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Fertilizer History</label>
                <textarea name="fertilizer_history" class="form-control" rows="2">{{ $batch->fertilizer_history }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Pesticide History</label>
                <textarea name="pesticide_history" class="form-control" rows="2">{{ $batch->pesticide_history }}</textarea>
            </div>
        </div>

        {{-- Section 3: Quantity & Safety --}}
        <h4 class="text-success mb-3 border-bottom pb-2"><i class="bi bi-box-seam"></i> Quantity & Quality Analysis</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Total Quantity (KG/Unit)</label>
                <input type="number" step="0.01" name="total_quantity" value="{{ $batch->total_quantity }}" class="form-control border-success" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Moisture Level (%)</label>
                <input type="text" name="moisture_level" value="{{ $batch->moisture_level }}" class="form-control border-success">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Manufacturing Date</label>
                <input type="date" name="manufacturing_date" value="{{ $batch->manufacturing_date }}" class="form-control border-success" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Harvest Date</label>
                <input type="date" name="harvest_date" value="{{ $batch->harvest_date }}" class="form-control border-success">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-secondary">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ $batch->expiry_date }}" class="form-control border-secondary">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-primary">Quality Grade (By QC)</label>
                <select name="quality_grade" class="form-select border-primary">
                    <option value="A" {{ $batch->quality_grade == 'A' ? 'selected' : '' }}>Grade A (Export Quality)</option>
                    <option value="B" {{ $batch->quality_grade == 'B' ? 'selected' : '' }}>Grade B (Local Premium)</option>
                    <option value="C" {{ $batch->quality_grade == 'C' ? 'selected' : '' }}>Grade C (Standard)</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                 <label class="form-label font-weight-bold">QC Status</label>
                 <div class="mt-2">
                    <span class="badge {{ $batch->qc_status == 'approved' ? 'bg-success' : ($batch->qc_status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                        {{ strtoupper($batch->qc_status) }}
                    </span>
                 </div>
            </div>
        </div>

        <div class="mt-3 border-top pt-3">
            <button type="submit" class="btn btn-success px-5 shadow-sm">
                <i class="bi bi-check-circle"></i> Update Batch Records
            </button>
            <a href="{{ URL('admin/batches') }}" class="btn btn-outline-secondary px-3">Cancel</a>
        </div>
    </form>
</div>

@endsection
