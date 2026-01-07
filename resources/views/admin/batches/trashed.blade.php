@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-1 mt-2">
        <h2 class="mb-0 text-danger mt-2"><i class="bi bi-trash3"></i> Trashed Batches</h2>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary shadow-sm mt-2">
            <i class="bi bi-arrow-left"></i> Back to Batch List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-danger">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Batch Info</th>
                            <th scope="col">Product</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Deleted At</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batches as $batch)
                            <tr>
                                <td>{{ $batch->id }}</td>
                                <td>
                                    <strong class="text-primary">{{ $batch->batch_no }}</strong><br>
                                    <small class="text-muted">Farmer ID: {{ $batch->initial_farmer_id }}</small>
                                </td>
                                <td>
                                    {{ $batch->product->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border">
                                        {{ $batch->total_quantity }} Units
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger small">
                                        <i class="bi bi-calendar-x"></i> {{ $batch->deleted_at->format('d M, Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ URL('admin/batches/restore/'.$batch->id) }}" class="btn btn-sm btn-outline-success px-3" title="Restore Batch">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>

                                        <form action="{{ URL('admin/batches/force-delete/'.$batch->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permanently? This cannot be undone!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3" title="Permanent Delete">
                                                <i class="bi bi-x-circle"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-trash fs-2"></i><br>
                                    No Trashed Batch Records Found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $batches->appends(request()->query())->links() }}
    </div>
@endsection
