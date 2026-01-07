@extends("admin.layout.erp.app")
@section("content")

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Create Miller / Supplier</h1>
        <a href="{{URL('miller')}}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{URL("miller/store")}}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h5 class="text-muted mb-3">Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter miller name" value="{{old('name')}}">
                @error("name")
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{old('phone')}}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{old('email')}}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input type="text" name="nid" class="form-control" placeholder="Enter NID Number" value="{{old('nid')}}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label font-weight-bold">Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Enter factory/office address">{{old('address')}}</textarea>
        </div>

        <hr class="my-4">

        <h5 class="text-info mb-3"><i class="bi bi-building"></i> Miller Specific Details</h5>
        <div class="row bg-white p-3 border rounded mx-0">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-info">Factory License Number</label>
                <input type="text" name="factory_license" class="form-control border-info" placeholder="Enter license no" value="{{old('factory_license')}}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-info">Milling Capacity (Tons)</label>
                <input type="text" name="milling_capacity" class="form-control border-info" placeholder="e.g. 500" value="{{old('milling_capacity')}}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-info">Storage Unit Type</label>
                <select name="storage_unit_type" class="form-select border-info">
                    <option value="">Select Type</option>
                    <option value="cold_storage" {{old('storage_unit_type') == 'cold_storage' ? 'selected' : ''}}>Cold Storage</option>
                    <option value="warehouse" {{old('storage_unit_type') == 'warehouse' ? 'selected' : ''}}>Warehouse</option>
                    <option value="silo" {{old('storage_unit_type') == 'silo' ? 'selected' : ''}}>Silo</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-save"></i> Save Miller Profile
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">Reset</button>
        </div>

    </form>

@endsection
