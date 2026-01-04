@props(['product'])

<tr>
    <td>{{ $product->id }}</td>
    <td>
        @if($product->image)
            <img src="{{ asset('storage/photo/product/' . $product->image) }}" alt="Product Image" width="60" class="img-thumbnail">
        @else
            <img src="{{ asset('images/no-image.png') }}" alt="No Image" width="60" class="img-thumbnail">
        @endif
    </td>
    <td>
        <strong>{{ $product->name }}</strong><br>
        <small class="text-muted">{{ $product->product_type }}</small>
    </td>
    <td>{{ $product->category->name ?? 'N/A' }}</td>
    <td><span class="badge bg-light text-dark border">{{ $product->sku }}</span></td>
    <td>{{ number_format($product->sale_price, 2) }}</td>
    <td>
        @if($product->stock <= $product->alert_quantity)
            <span class="text-danger fw-bold">{{ $product->stock }} {{ $product->unit->name ?? '' }} (Low)</span>
        @else
            <span>{{ $product->stock }} {{ $product->unit->name ?? '' }}</span>
        @endif
    </td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ url('product/edit', $product->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-edit"></i> Edit
            </a>

            <form action="{{ url('product/delete', $product->id) }}" method="post">
                @csrf
                @method('delete')
                <button type="submit" onclick="return confirm('Are you sure you want to move this product to trash?')" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </td>
</tr>
