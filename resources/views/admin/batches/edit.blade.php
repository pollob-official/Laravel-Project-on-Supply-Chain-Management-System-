@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-primary"><i class="bi bi-pencil-square"></i> Edit Product Batch: <span class="text-dark">{{ $batch->batch_no }}</span></h3>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Batch List
        </a>
    </div>

    {{-- Update Route with ID and PUT Method --}}
    <form action="{{ route('batches.update', $batch->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-white">
        @csrf
        @method('POST')

        {{-- Section 1: Identification & GPS --}}
        <h4 class="text-muted mb-3 border-bottom pb-2">Batch Identification & Farm GPS</h4>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select border-primary" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $batch->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Source Farmer <span class="text-danger">*</span></label>
                <select name="initial_farmer_id" class="form-select border-primary" required>
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

        {{-- GPS Coordinates (ReadOnly for Integrity) --}}
        <div class="row bg-light p-3 rounded mb-4 mx-1 border">
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold text-primary"><i class="bi bi-geo"></i> Latitude</label>
                <input type="text" name="latitude" value="{{ $batch->latitude }}" class="form-control border-primary bg-white" readonly>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold text-primary"><i class="bi bi-geo"></i> Longitude</label>
                <input type="text" name="longitude" value="{{ $batch->longitude }}" class="form-control border-primary bg-white" readonly>
            </div>
        </div>

        {{-- Section 2: Cultivation & Seed Info --}}
        <h4 class="text-info mb-3 border-bottom pb-2 mt-2"><i class="bi bi-seedling"></i> Seed & Cultivation Info</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold small">Seed Brand</label>
                <input type="text" name="seed_brand" value="{{ $batch->seed_brand }}" class="form-control border-info">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold small">Seed Variety</label>
                <input type="text" name="seed_variety" value="{{ $batch->seed_variety }}" class="form-control border-info">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger small">Sowing Date</label>
                <input type="date" name="sowing_date" value="{{ $batch->sowing_date }}" class="form-control border-danger">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger small">Last Pesticide Date</label>
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

        {{-- Section 3: Quantity & Quality Analysis --}}
        <h4 class="text-success mb-3 border-bottom pb-2 mt-2"><i class="bi bi-box-seam"></i> Quantity & Quality Analysis</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Total Quantity</label>
                <input type="number" step="0.01" name="total_quantity" value="{{ $batch->total_quantity }}" class="form-control border-success" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold small">Moisture Level (%)</label>
                <input type="text" name="moisture_level" value="{{ $batch->moisture_level }}" class="form-control border-success">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Manufacturing Date</label>
                <input type="date" name="manufacturing_date" value="{{ $batch->manufacturing_date }}" class="form-control border-success" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold small">Harvest Date</label>
                <input type="date" name="harvest_date" value="{{ $batch->harvest_date }}" class="form-control border-success">
            </div>
        </div>

        {{-- Section 4: QC & Final Status --}}
        <h4 class="text-warning mb-3 border-bottom pb-2 mt-2"><i class="bi bi-patch-check"></i> QC Review & Status</h4>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-secondary">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ $batch->expiry_date }}" class="form-control border-secondary">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-primary">Quality Grade</label>
                <select name="quality_grade" class="form-select border-primary">
                    <option value="A" {{ $batch->quality_grade == 'A' ? 'selected' : '' }}>Grade A (Export Quality)</option>
                    <option value="B" {{ $batch->quality_grade == 'B' ? 'selected' : '' }}>Grade B (Local Premium)</option>
                    <option value="C" {{ $batch->quality_grade == 'C' ? 'selected' : '' }}>Grade C (Standard)</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Current QC Status</label>
                <div class="mt-2">
                    <span class="badge py-2 px-3 {{ $batch->qc_status == 'approved' ? 'bg-success' : ($batch->qc_status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        <i class="bi {{ $batch->qc_status == 'approved' ? 'bi-check-circle' : 'bi-exclamation-triangle' }}"></i>
                        {{ strtoupper($batch->qc_status) }}
                    </span>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">QC Remarks (Internal Use)</label>
                <textarea name="qc_remarks" class="form-control border-warning" rows="2">{{ $batch->qc_remarks }}</textarea>
            </div>
        </div>

        <div class="mt-4 border-top pt-3 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                <i class="bi bi-save"></i> Save & Update Lifecycle Data
            </button>
            <a href="{{ URL('admin/batches') }}" class="btn btn-outline-secondary btn-lg px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
