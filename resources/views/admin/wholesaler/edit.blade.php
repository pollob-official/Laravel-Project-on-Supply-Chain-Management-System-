@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-pencil-square"></i> Edit Wholesaler Profile</h3>
        <a href="{{ URL('admin/wholesaler') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ URL('admin/wholesaler/update', $wholesaler->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf
        @method('PUT')

        <h5 class="text-muted mb-3">Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Wholesaler Name</label>
                <input value="{{ $wholesaler->name }}" type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input value="{{ $wholesaler->email }}" type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone</label>
                <input value="{{ $wholesaler->phone }}" type="text" name="phone" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input value="{{ $wholesaler->nid }}" type="text" name="nid" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Full Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $wholesaler->address }}</textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="text-warning mb-3"><i class="bi bi-shop"></i> Wholesaler Business Details</h5>
        <div class="row bg-white p-3 border rounded mx-0">
            <div class="col-md-4 mb-3">
                <label class="form-label text-warning font-weight-bold">Trade License Number</label>
                <input value="{{ $wholesaler->wholesaler->trade_license ?? '' }}" type="text" name="trade_license" class="form-control border-warning" placeholder="Enter license no">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-warning font-weight-bold">Warehouse Location</label>
                <input value="{{ $wholesaler->wholesaler->warehouse_location ?? '' }}" type="text" name="warehouse_location" class="form-control border-warning" placeholder="e.g. Khatunganj, Chattogram">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-warning font-weight-bold">Total Manpower</label>
                <input value="{{ $wholesaler->wholesaler->total_manpower ?? 0 }}" type="number" name="total_manpower" class="form-control border-warning">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bi bi-save"></i> Update Wholesaler Info
            </button>
            <a href="{{ URL('admin/wholesaler') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
