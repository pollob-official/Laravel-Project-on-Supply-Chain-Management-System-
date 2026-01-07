@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3><i class="bi bi-pencil-square"></i> Edit Batch Records</h3>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Batch List
        </a>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ URL('admin/batches/update', $batch->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h4 class="text-muted mb-3">Batch Identity (Non-Editable)</h4>
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label font-weight-bold">Batch Number</label>
                <input value="{{ $batch->batch_no }}" type="text" class="form-control bg-white" readonly>
            </div>

            <div class="col-md-8 mb-2 text-center">
                <label class="form-label font-weight-bold d-block">Current QR Code</label>
                @if($batch->qr_code)
                    <img src="{{ asset($batch->qr_code) }}" alt="QR" width="80" class="border p-1 bg-white shadow-sm">
                @else
                    <span class="text-danger">No QR Generated</span>
                @endif
            </div>
        </div>

        <hr class="my-3">

        <h4 class="text-primary mb-3"><i class="bi bi-box-seam"></i> Batch Specifications</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $batch->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Source Farmer <span class="text-danger">*</span></label>
                <select name="initial_farmer_id" class="form-select form-control" required>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}" {{ $batch->initial_farmer_id == $farmer->id ? 'selected' : '' }}>
                            {{ $farmer->name }} ({{ $farmer->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-2">
                <label class="form-label font-weight-bold">Total Quantity</label>
                <div class="input-group">
                    <input value="{{ $batch->total_quantity }}" type="number" step="0.01" name="total_quantity" class="form-control" required>
                    <span class="input-group-text bg-primary text-white">Units</span>
                </div>
            </div>

            <div class="col-md-4 mb-2">
                <label class="form-label font-weight-bold">Manufacturing Date</label>
                <input value="{{ $batch->manufacturing_date }}" type="date" name="manufacturing_date" class="form-control" required>
            </div>

            <div class="col-md-4 mb-2">
                <label class="form-label font-weight-bold">Expiry Date</label>
                <input value="{{ $batch->expiry_date }}" type="date" name="expiry_date" class="form-control">
            </div>
        </div>

        <div class="mt-4 border-top pt-3">
            <button type="submit" class="btn btn-success px-5 shadow-sm">
                <i class="bi bi-check-circle"></i> Save Batch Updates
            </button>
            <a href="{{ URL('admin/batches') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
