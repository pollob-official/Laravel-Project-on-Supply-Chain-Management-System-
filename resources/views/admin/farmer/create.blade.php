@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3><i class="bi bi-person-plus"></i> Add New Farmer</h3>
        <a href="{{ URL('admin/farmer') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Farmer List
        </a>
    </div>

    <form action="{{ URL('admin/farmer/save') }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h4 class="text-muted mb-3">Basic Information</h4>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter farmer name">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="email@example.com">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="017xxxxxxxx">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold">NID Number</label>
                <input type="text" name="nid" class="form-control" placeholder="National ID number">
            </div>

            <div class="col-md-12 mb-2">
                <label class="form-label font-weight-bold">Permanent Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Village, Post, Upazila..."></textarea>
            </div>
        </div>

        <hr class="my-2">

        <h4 class="text-success mb-3"><i class="bi bi-tree"></i> Farming Details (Sub-table Data)</h4>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label text-success font-weight-bold">Land Area (Decimal)</label>
                <input type="text" name="land_area" class="form-control border-success" placeholder="e.g. 50">
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label text-success font-weight-bold">Farmer Card Number</label>
                <input type="text" name="farmer_card_no" class="form-control border-success" placeholder="Card ID number">
            </div>

            <div class="col-md-12 mb-2">
                <label class="form-label text-success font-weight-bold">Crop History / Previous Crops</label>
                <textarea name="crop_history" class="form-control border-success" rows="3" placeholder="আগে কী কী চাষ করেছেন তার বিবরণ..."></textarea>
            </div>
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bi bi-save"></i> Save Farmer Profile
            </button>
            <button type="reset" class="btn btn-outline-secondary px-3">Reset</button>
        </div>
    </form>
</div>

@endsection
