@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-primary"><i class="bi bi-qr-code-scan"></i> Generate New Product Batch</h3>
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

    <form action="{{ URL('admin/batches/store') }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h4 class="text-muted mb-3 border-bottom pb-2">Batch Identification & Logic</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select form-control" required>
                    <option value="">-- Choose Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Source Farmer (Initial) <span class="text-danger">*</span></label>
                <select name="initial_farmer_id" class="form-select form-control" required>
                    <option value="">-- Choose Farmer --</option>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}">{{ $farmer->name }} ({{ $farmer->phone }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <div class="alert alert-info py-2 small mb-0">
                    <i class="bi bi-info-circle"></i> <strong>Note:</strong> Batch Number and QR Code will be automatically generated upon saving.
                </div>
            </div>
        </div>

        <hr class="my-2">

        <h4 class="text-success mb-3"><i class="bi bi-box-seam"></i> Quantity & Timeline</h4>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-success font-weight-bold">Total Quantity (Unit/KG)</label>
                <input type="number" step="0.01" name="total_quantity" class="form-control border-success" placeholder="e.g. 500.00" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-success font-weight-bold">Manufacturing Date</label>
                <input type="date" name="manufacturing_date" class="form-control border-success" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-success font-weight-bold">Expiry Date (Optional)</label>
                <input type="date" name="expiry_date" class="form-control border-success">
            </div>
        </div>

        <div class="mt-3 border-top pt-3">
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bi bi-qr-code"></i> Generate Batch & QR
            </button>
            <button type="reset" class="btn btn-outline-secondary px-3">Clear Form</button>
        </div>
    </form>
</div>

@endsection
