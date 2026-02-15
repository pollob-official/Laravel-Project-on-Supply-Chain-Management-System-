@extends("admin.layout.erp.app")
@section("content")

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-box-seam"></i> Add New Product</h3>
        <a href="{{ URL('admin/product') }}" class="btn btn-secondary shadow-sm">
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

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 bg-light">
            <form action="{{ URL('admin/product/save') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Product Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Premium Miniket Rice" value="{{ old('name') }}" required>
                    </div>

                    {{-- SKU / Barcode --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">SKU / Code <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" placeholder="RICE-MIN-001" value="{{ old('sku') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">GTIN / HS Code</label>
                        <div class="d-flex gap-2">
                            <input type="text" name="gtin" class="form-control" placeholder="GTIN" value="{{ old('gtin') }}">
                            <input type="text" name="hs_code" class="form-control" placeholder="HS Code" value="{{ old('hs_code') }}">
                        </div>
                    </div>

                    {{-- Product Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">Product Type <span class="text-danger">*</span></label>
                        <select name="product_type" class="form-select">
                            <option value="finish_good" {{ old('product_type') == 'finish_good' ? 'selected' : '' }}>Finish Good</option>
                            <option value="raw_material" {{ old('product_type') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                            <option value="by_product" {{ old('product_type') == 'by_product' ? 'selected' : '' }}>By-Product</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    {{-- Category --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Unit --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" class="form-select" required>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Image --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="row">
                    {{-- Purchase Price --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Purchase Price (কেনা দাম)</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', '0.00') }}">
                        </div>
                    </div>

                    {{-- Sale Price --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Sale Price (বিক্রয় মূল্য)</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', '0.00') }}">
                        </div>
                    </div>

                    {{-- Alert Quantity --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Alert Quantity (স্টক অ্যালার্ট)</label>
                        <input type="number" name="alert_quantity" class="form-control" placeholder="e.g. 10" value="{{ old('alert_quantity', '0') }}">
                        <small class="text-muted">এই পরিমাণের নিচে স্টক আসলে সিগনাল দিবে।</small>
                    </div>
                </div>

                <div class="mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="bi bi-save"></i> Save Product
                    </button>
                    <button type="reset" class="btn btn-outline-dark btn-lg px-4">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
