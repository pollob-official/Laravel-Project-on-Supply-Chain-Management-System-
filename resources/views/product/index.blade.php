@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h1 class="mb-2">Product List</h1>

    {{-- Search Form --}}
    <form action="{{URL("product")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, SKU or type...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="mb-3">
        <x-button :url="URL('product/create')" type="primary">
            <i class="bi bi-plus-lg"></i> Add Product
        </x-button>
        <a href="{{ URL('product/trashed') }}" class="btn btn-outline-danger">
            <i class="bi bi-trash"></i> View Trash
        </a>
    </div>

    <table class="table mt-3 align-middle table-hover">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Product Name</th>
                <th scope="col">Category</th>
                <th scope="col">Type</th>
                <th scope="col">Price (Buy/Sale)</th>
                <th scope="col">Stock Alert</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($products) > 0)
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/photo/product/'.$product->image) }}" alt="Product" class="rounded border" width="50" height="50" style="object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/no-image.png') }}" alt="No Image" class="rounded border" width="50">
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                        <td>
                            @php
                                $typeColor = [
                                    'finish_good'  => 'success',
                                    'raw_material' => 'info',
                                    'by_product'   => 'warning'
                                ][$product->product_type] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{$typeColor}}">{{ ucfirst(str_replace('_', ' ', $product->product_type)) }}</span>
                        </td>
                        <td>
                            <small class="d-block">Buy: <strong>{{ number_format($product->purchase_price, 2) }}</strong></small>
                            <small class="d-block text-primary">Sale: <strong>{{ number_format($product->sale_price, 2) }}</strong></small>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger">
                                <i class="bi bi-exclamation-triangle"></i> {{ $product->alert_quantity }} {{ $product->unit->short_name ?? '' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ URL('product/edit/'.$product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ URL('product/delete/'.$product->id) }}" method="POST" onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Soft Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center py-5 text-danger">No Products Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $products->appends(request()->query())->links() }}
    </div>
@endsection
