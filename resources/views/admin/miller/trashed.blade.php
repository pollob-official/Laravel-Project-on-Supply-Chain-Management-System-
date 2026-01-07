@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0 text-danger"><i class="bi bi-trash"></i> Trashed Miller List</h3>
        <a href="{{ URL('miller') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Miller List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Miller Name</th>
                        <th scope="col">License</th>
                        <th scope="col">Capacity</th>
                        <th scope="col">Storage Type</th>
                        <th scope="col">Deleted At</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($millers) > 0)
                        @foreach ($millers as $miller)
                            <tr>
                                <td>{{ $miller->id }}</td>
                                <td>
                                    <strong>{{ $miller->name }}</strong><br>
                                    <small class="text-muted">{{ $miller->phone }}</small>
                                </td>
                                <td>
                                    <code class="text-primary">{{ $miller->miller->factory_license ?? 'N/A' }}</code>
                                </td>
                                <td>{{ $miller->miller->milling_capacity ?? '0' }} Tons</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($miller->miller->storage_unit_type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger small">
                                        {{ $miller->deleted_at->format('d M, Y h:i A') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ URL('miller/restore/'.$miller->id) }}" class="btn btn-sm btn-success px-3" onclick="return confirm('Restore this miller profile?')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>

                                        <form action="{{ URL('miller/force-delete/'.$miller->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-3">
                                                <i class="bi bi-x-circle"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-trash fs-2"></i><br>
                                No trashed records found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $millers->links() }}
    </div>
@endsection
