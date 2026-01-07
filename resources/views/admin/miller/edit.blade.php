@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-pencil-square"></i> Edit Miller Profile</h3>
        <a href="{{ URL('miller') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ URL('miller/update', $miller->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf
        @method('POST') {{-- আপডেট করার জন্য PUT মেথড ব্যবহার করা হয়েছে --}}

        <h5 class="text-muted mb-3">Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Name</label>
                <input value="{{ $miller->name }}" type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input value="{{ $miller->email }}" type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone</label>
                <input value="{{ $miller->phone }}" type="text" name="phone" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input value="{{ $miller->nid }}" type="text" name="nid" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $miller->address }}</textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="text-info mb-3"><i class="bi bi-building"></i> Miller Specific Details</h5>
        <div class="row bg-white p-3 border rounded mx-0">
            <div class="col-md-4 mb-3">
                <label class="form-label text-info font-weight-bold">Factory License Number</label>
                <input value="{{ $miller->miller->factory_license ?? '' }}" type="text" name="factory_license" class="form-control border-info">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-info font-weight-bold">Milling Capacity (Tons)</label>
                <input value="{{ $miller->miller->milling_capacity ?? '' }}" type="text" name="milling_capacity" class="form-control border-info">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-info font-weight-bold">Storage Unit Type</label>
                <select name="storage_unit_type" class="form-select border-info">
                    <option value="">Select Type</option>
                    <option value="cold_storage" {{ (isset($miller->miller) && $miller->miller->storage_unit_type == 'cold_storage') ? 'selected' : '' }}>Cold Storage</option>
                    <option value="warehouse" {{ (isset($miller->miller) && $miller->miller->storage_unit_type == 'warehouse') ? 'selected' : '' }}>Warehouse</option>
                    <option value="silo" {{ (isset($miller->miller) && $miller->miller->storage_unit_type == 'silo') ? 'selected' : '' }}>Silo</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bi bi-save"></i> Update Miller Profile
            </button>
            <a href="{{ URL('miller') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
