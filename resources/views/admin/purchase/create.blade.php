@extends("admin.layout.erp.app")
@section("content")

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-cart-plus"></i> Purchase / Add Stock</h3>
        <a href="{{ URL('admin/product') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Products
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
            <form action="{{ URL('admin/purchase/store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Select Product --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Select Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select border-primary shadow-sm" required>
                            <option value="">-- Choose a Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Current Stock: {{ $product->stock }} {{ $product->unit->short_name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Purchase Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row">
                    {{-- Quantity --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Quantity (পরিমাণ) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="qty" class="form-control" placeholder="e.g. 50" min="1" required>
                            <span class="input-group-text bg-white">Units</span>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Purchase Price (ইউনিট প্রতি কেনা দাম) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">৳</span>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-top pt-3 text-end">
                    <button type="reset" class="btn btn-outline-dark btn-lg px-4 me-2">Reset</button>
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                        <i class="bi bi-check-circle"></i> Confirm Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
