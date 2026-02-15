@extends("admin.layout.erp.app")
@section("content")

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-pencil-square"></i> Edit Product: {{ $product->name }}</h3>
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
            <form action="{{ URL('admin/product/update/'.$product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="row">
                    {{-- Product Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    {{-- SKU / Barcode --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">SKU / Code <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">GTIN / HS Code</label>
                        <div class="d-flex gap-2">
                            <input type="text" name="gtin" class="form-control" placeholder="GTIN" value="{{ old('gtin', $product->gtin) }}">
                            <input type="text" name="hs_code" class="form-control" placeholder="HS Code" value="{{ old('hs_code', $product->hs_code) }}">
                        </div>
                    </div>

                    {{-- Product Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">Product Type <span class="text-danger">*</span></label>
                        <select name="product_type" class="form-select">
                            <option value="finish_good" {{ $product->product_type == 'finish_good' ? 'selected' : '' }}>Finish Good</option>
                            <option value="raw_material" {{ $product->product_type == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                            <option value="by_product" {{ $product->product_type == 'by_product' ? 'selected' : '' }}>By-Product</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    {{-- Category --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Unit --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" class="form-select" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->short_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Image Upload & Preview --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Update Image</label>
                        <input type="file" name="image" class="form-control mb-2" accept="image/*">
                        @if($product->image)
                            <div class="mt-2 text-center border p-1 rounded bg-white" style="width: 100px;">
                                <small class="d-block text-muted">Current Image</small>
                                <img src="{{ asset('storage/photo/product/'.$product->image) }}" width="80" class="rounded">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    {{-- Purchase Price --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Purchase Price</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}">
                        </div>
                    </div>

                    {{-- Sale Price --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Sale Price</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                        </div>
                    </div>

                    {{-- Alert Quantity --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Alert Quantity</label>
                        <input type="number" name="alert_quantity" class="form-control" value="{{ old('alert_quantity', $product->alert_quantity) }}">
                    </div>
                </div>

                <div class="mt-4 border-top pt-3 text-end">
                    <a href="{{ URL('admin/product') }}" class="btn btn-outline-secondary btn-lg px-4">Cancel</a>
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                        <i class="bi bi-check-circle"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
