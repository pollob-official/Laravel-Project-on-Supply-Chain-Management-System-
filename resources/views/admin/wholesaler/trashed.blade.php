@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0 text-danger"><i class="bi bi-trash"></i> Trashed Wholesalers</h3>
        <a href="{{ URL('admin/wholesaler') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Wholesaler List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Wholesaler Details</th>
                        <th scope="col">Trade License</th>
                        <th scope="col">Warehouse Location</th>
                        <th scope="col">Deleted At</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($wholesalers) > 0)
                        @foreach ($wholesalers as $wholesaler)
                            <tr>
                                <td>{{ $wholesaler->id }}</td>
                                <td>
                                    <strong>{{ $wholesaler->name }}</strong><br>
                                    <small class="text-muted">{{ $wholesaler->phone }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-dark border border-warning">
                                        {{ $wholesaler->wholesaler->trade_license ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $wholesaler->wholesaler->warehouse_location ?? 'Not Set' }}</td>
                                <td>
                                    <span class="text-danger small">
                                        <i class="bi bi-calendar-x"></i> {{ $wholesaler->deleted_at->format('d M, Y h:i A') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ URL('admin/wholesaler/restore/'.$wholesaler->id) }}" class="btn btn-sm btn-outline-success px-3" title="Restore Wholesaler">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>

                                        <form action="{{ URL('admin/wholesaler/force-delete/'.$wholesaler->id) }}" method="POST" onsubmit="return confirm('WARNING: This will delete the wholesaler permanently! Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3" title="Permanent Delete">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fs-2"></i><br>
                                No records found in trash.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $wholesalers->links() }}
    </div>
@endsection
