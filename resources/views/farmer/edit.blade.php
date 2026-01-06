@extends("layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3><i class="bi bi-person-badge"></i> Edit Farmer Profile</h3>
        <a href="{{ URL('farmer') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Farmer List
        </a>
    </div>

    <form action="{{ URL('farmer/update', $farmer->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h4 class="text-muted mb-3">Basic Information</h4>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">Full Name</label>
                <input value="{{ $farmer->name }}" type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">Email Address</label>
                <input value="{{ $farmer->email }}" type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">Phone Number</label>
                <input value="{{ $farmer->phone }}" type="text" name="phone" class="form-control" required>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">NID Number</label>
                <input value="{{ $farmer->nid }}" type="text" name="nid" class="form-control">
            </div>

            <div class="col-md-12 mb-2">
                <label class="form-label font-weight-bold">Permanent Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $farmer->address }}</textarea>
            </div>
        </div>

        <hr class="my-2">

        <h4 class="text-success mb-3"><i class="bi bi-tree"></i> Farming Details</h4>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label text-success font-weight-bold">Land Area (Decimal/Acre)</label>
                <div class="input-group">
                    <input value="{{ $farmer->farmer->land_area ?? '' }}" type="text" name="land_area" class="form-control border-success">
                    <span class="input-group-text bg-success text-white">Unit</span>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label text-success font-weight-bold">Farmer Card Number</label>
                <input value="{{ $farmer->farmer->farmer_card_no ?? '' }}" type="text" name="farmer_card_no" class="form-control border-success">
            </div>

            <div class="col-md-12 mb-2">
                <label class="form-label text-success font-weight-bold">Crop History / Details</label>
                <textarea name="crop_history" class="form-control border-success" rows="3" placeholder="Enter previous crop details...">{{ $farmer->farmer->crop_history ?? '' }}</textarea>
            </div>
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-success px-5 shadow-sm">
                <i class="bi bi-check-circle"></i> Update Farmer Records
            </button>
        </div>
    </form>
</div>

@endsection
