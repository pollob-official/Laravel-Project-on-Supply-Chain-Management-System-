@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-trash3 text-danger"></i> Trashed Products</h3>
        <a href="{{ URL('product') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Product List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Deleted At</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/photo/product/'.$product->image) }}" class="rounded opacity-75" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <img src="{{ asset('assets/images/no-image.png') }}" class="rounded" width="50">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small class="text-muted">{{ $product->category->name ?? 'No Category' }}</small>
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <span class="text-muted small">
                                        {{ \Carbon\Carbon::parse($product->deleted_at)->format('d M, Y') }}<br>
                                        {{ \Carbon\Carbon::parse($product->deleted_at)->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- Restore Action --}}
                                        <a href="{{ URL('product/restore/'.$product->id) }}" class="btn btn-sm btn-outline-success" title="Restore Product">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>

                                        {{-- Permanent Delete Action --}}
                                        <form action="{{ URL('product/force-delete/'.$product->id) }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete the product and its image. Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Permanently">
                                                <i class="bi bi-x-circle"></i> Permanent Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-recycle display-4 text-muted"></i>
                                    <p class="mt-2 text-muted">Trash is empty!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection
