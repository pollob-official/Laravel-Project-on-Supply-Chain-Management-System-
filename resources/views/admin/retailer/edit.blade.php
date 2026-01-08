@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-pencil-square"></i> Edit Retailer Profile</h3>
        <a href="{{ URL('admin/retailer') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    {{-- পূর্বের MethodNotAllowedHttpException এড়াতে রাউটটি PUT মেথডে দেয়া হয়েছে --}}
    <form action="{{ URL('admin/retailer/update', $retailer->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf
        @method('POST')

        <h5 class="text-muted mb-3">Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Retailer Name</label>
                <input value="{{ $retailer->name }}" type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input value="{{ $retailer->email }}" type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone</label>
                <input value="{{ $retailer->phone }}" type="text" name="phone" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input value="{{ $retailer->nid }}" type="text" name="nid" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Full Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $retailer->address }}</textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="text-primary mb-3"><i class="bi bi-shop"></i> Retailer Business Details</h5>
        <div class="row bg-white p-3 border rounded mx-0">
            <div class="col-md-4 mb-3">
                <label class="form-label text-primary font-weight-bold">Shop Name</label>
                <input value="{{ $retailer->retailer->shop_name ?? '' }}" type="text" name="shop_name" class="form-control border-primary" placeholder="Enter shop name">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-primary font-weight-bold">TIN Number</label>
                <input value="{{ $retailer->retailer->tin_no ?? '' }}" type="text" name="tin_no" class="form-control border-primary" placeholder="Enter TIN no">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-primary font-weight-bold">Market Name</label>
                <input value="{{ $retailer->retailer->market_name ?? '' }}" type="text" name="market_name" class="form-control border-primary" placeholder="e.g. New Market, Dhaka">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bi bi-save"></i> Update Retailer Info
            </button>
            <a href="{{ URL('admin/retailer') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
