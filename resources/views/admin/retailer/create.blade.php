@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-plus-circle"></i> Create New Retailer</h3>
        <a href="{{ URL('retailer') }}" class="btn btn-secondary shadow-sm">
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

    {{-- আপনার রাউট অনুযায়ী retailer/store রুটে ডাটা যাবে --}}
    <form action="{{ URL('retailer/store') }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <h5 class="text-muted mb-3">Basic Information</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Retailer Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter full name" value="{{ old('name') }}" required>
                @error("name") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com" value="{{ old('email') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" placeholder="017XXXXXXXX" value="{{ old('phone') }}" required>
                @error("phone") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input type="text" name="nid" class="form-control" placeholder="Enter NID" value="{{ old('nid') }}">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Full Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="House, Road, Area...">{{ old('address') }}</textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="text-primary mb-3"><i class="bi bi-shop"></i> Retailer Business Details</h5>
        <div class="row bg-white p-3 border rounded mx-0 shadow-sm">
            <div class="col-md-4 mb-3">
                <label class="form-label text-primary font-weight-bold">Shop Name <span class="text-danger">*</span></label>
                <input type="text" name="shop_name" class="form-control border-primary" placeholder="Enter shop name" value="{{ old('shop_name') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-primary font-weight-bold">TIN Number</label>
                <input type="text" name="tin_no" class="form-control border-primary" placeholder="Enter TIN" value="{{ old('tin_no') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label text-primary font-weight-bold">Market Name</label>
                <input type="text" name="market_name" class="form-control border-primary" placeholder="e.g. Karwan Bazar" value="{{ old('market_name') }}">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-save"></i> Save Retailer
            </button>
            <a href="{{ URL('retailer') }}" class="btn btn-outline-secondary btn-lg px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
