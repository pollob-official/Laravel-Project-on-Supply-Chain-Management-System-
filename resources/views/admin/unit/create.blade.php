@extends("admin.layout.erp.app")
@section("content")

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Add New Measurement Unit</h3>
        <a href="{{ URL('admin/unit') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 bg-light">
            <form action="{{ URL('admin/unit/save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Kilogram, Sack, Drum" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">Short Name <span class="text-danger">*</span></label>
                        <input type="text" name="short_name" class="form-control" placeholder="KG, Bag, Ltr" value="{{ old('short_name') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">Base Value <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="base_unit_value" class="form-control" placeholder="1.00" value="{{ old('base_unit_value', '1.00') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="bi bi-save"></i> Save Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
