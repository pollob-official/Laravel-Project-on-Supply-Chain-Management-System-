@extends("admin.layout.erp.app")
@section("content")

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-shop text-warning"></i> Create Wholesaler</h1>
        <a href="{{URL('wholesaler')}}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{URL("wholesaler/store")}}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h5 class="text-muted mb-3">General Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter wholesaler/business name" value="{{old('name')}}">
                @error("name")
                    <span class="text-danger small">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" placeholder="Enter mobile number" value="{{old('phone')}}">
                @error("phone")
                    <span class="text-danger small">{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{old('email')}}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input type="text" name="nid" class="form-control" placeholder="Enter NID" value="{{old('nid')}}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label font-weight-bold">Office/Warehouse Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Enter full address">{{old('address')}}</textarea>
        </div>

        <hr class="my-4">

        <h5 class="text-warning mb-3"><i class="bi bi-info-circle"></i> Wholesaler Business Details</h5>
        <div class="row bg-white p-3 border rounded mx-0">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-dark">Trade License <span class="text-danger">*</span></label>
                <input type="text" name="trade_license" class="form-control border-warning" placeholder="License Number" value="{{old('trade_license')}}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-dark">Warehouse Location</label>
                <input type="text" name="warehouse_location" class="form-control border-warning" placeholder="e.g. Dhaka, Karwan Bazar" value="{{old('warehouse_location')}}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-dark">Total Manpower</label>
                <input type="number" name="total_manpower" class="form-control border-warning" placeholder="Number of employees" value="{{old('total_manpower', 0)}}">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-save"></i> Save Wholesaler Profile
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">Reset Form</button>
        </div>

    </form>

@endsection
