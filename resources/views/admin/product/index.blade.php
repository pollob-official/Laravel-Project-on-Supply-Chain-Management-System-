@extends('admin.layout.erp.app')
@section('content')
    <x-alert />

    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <div>
            <h2 class="text-success mb-0">Product Inventory</h2>
            <p class="text-muted small">Manage your supply chain products and stock alerts.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ URL('admin/purchase/create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-cart-plus"></i> Stock In (Purchase)
            </a>
            <x-button :url="URL('admin/product/create')" type="primary">
                <i class="bi bi-plus-lg"></i> Add Product
            </x-button>
            <a href="{{ URL('admin/product/trashed') }}" class="btn btn-outline-danger shadow-sm">
                <i class="bi bi-trash"></i> Trash Bin
            </a>
        </div>
    </div>

    {{-- Search Section --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form action="{{ URL('admin/product') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input value="{{ request('search') }}" type="text" name="search"
                            class="form-control border-start-0 ps-0"
                            placeholder="Search by name, SKU, category, or type...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-white" style="background-color:#0ae264;">
                        <tr>
                            <th scope="col" class="ps-3">#ID</th>
                            <th scope="col">Product Info</th>
                            <th scope="col">Category</th>
                            <th scope="col">Type</th>
                            <th scope="col">Pricing (Buy/Sale)</th>
                            <th scope="col">Stock Alert</th>
                            <th scope="col">Current Stock</th>
                            <th scope="col" class="text-center pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $isLowStock = $product->stock <= $product->alert_quantity;
                            @endphp
                            <tr class="{{ $isLowStock ? 'table-danger' : '' }}">
                                <td class="ps-3 text-muted fw-bold">{{ $product->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/photo/product/' . $product->image) }}"
                                                class="rounded border me-2" width="45" height="45"
                                                style="object-fit: cover;">
                                        @else
                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center me-2"
                                                style="width:45px; height:45px;">
                                                <i class="bi bi-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <small class="text-muted text-uppercase" style="font-size: 0.7rem;">SKU:
                                                {{ $product->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @php
                                        $typeColor = [
                                            'finish_good' => 'success',
                                            'raw_material' => 'info',
                                            'by_product' => 'warning',
                                        ][$product->product_type] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $typeColor }}-subtle text-{{ $typeColor }} border border-{{ $typeColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $product->product_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="text-muted"><i class="bi bi-cart-dash"></i> Buy:
                                            <strong>৳{{ number_format($product->purchase_price, 2) }}</strong></div>
                                        <div class="text-primary"><i class="bi bi-graph-up-arrow"></i> Sale:
                                            <strong>৳{{ number_format($product->sale_price, 2) }}</strong></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger mb-1"
                                            style="width: fit-content;">
                                            Min: {{ $product->alert_quantity }} {{ $product->unit->short_name ?? '' }}
                                        </span>
                                        <small class="text-muted italic" style="font-size: 0.65rem;">Reorder Point</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge rounded-pill {{ $isLowStock ? 'bg-danger' : 'bg-success' }} mb-1" style="width: fit-content;">
                                            {{ $product->stock }} {{ $product->unit->short_name ?? '' }}
                                        </span>
                                        @if($isLowStock)
                                            <small class="text-danger fw-bold pulse-text" style="font-size: 0.65rem;">
                                                <i class="bi bi-exclamation-triangle-fill"></i> LOW
                                            </small>
                                        @endif
                                    </div>
                                </td>

                                <td class="pe-3">
                                    <div class="d-flex gap-1 justify-content-center">
                                        {{-- Quick Stock In Button --}}
                                        <a href="{{ URL('admin/purchase/create?product_id=' . $product->id) }}"
                                            class="btn btn-sm btn-success shadow-sm" title="Stock In">
                                            <i class="bi bi-plus-circle"></i>
                                        </a>

                                        <a href="{{ URL('admin/product/edit/' . $product->id) }}"
                                            class="btn btn-sm btn-outline-primary shadow-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ URL('admin/product/delete/' . $product->id) }}" method="POST"
                                            onsubmit="return confirm('Move this product to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"
                                                title="Delete">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-box-seam display-4 text-muted opacity-25"></i>
                                    <p class="mt-3 text-secondary">No products found in inventory.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

    <style>
        .pulse-text {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
@endsection
