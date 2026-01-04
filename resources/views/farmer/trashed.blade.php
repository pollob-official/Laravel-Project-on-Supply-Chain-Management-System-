@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0 text-danger"><i class="bi bi-trash3"></i> Trashed Farmers</h3>
        <a href="{{ URL('farmer') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Farmer List
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Farmer Name</th>
                            <th scope="col">Land Area</th>
                            <th scope="col">Card No</th>
                            <th scope="col">Crop History</th>
                            <th scope="col">Deleted At</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($farmers as $farmer)
                            <tr>
                                <td>{{ $farmer->id }}</td>
                                <td>
                                    <strong>{{ $farmer->name }}</strong><br>
                                    <small class="text-muted">{{ $farmer->phone }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border">
                                        {{ $farmer->farmer->land_area ?? '0' }} Dec.
                                    </span>
                                </td>
                                <td>
                                    <code class="text-primary font-weight-bold">
                                        {{ $farmer->farmer->farmer_card_no ?? 'N/A' }}
                                    </code>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $farmer->farmer->crop_history ?? 'No Data' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="text-danger small">
                                        <i class="bi bi-calendar-x"></i> {{ $farmer->deleted_at->format('d M, Y') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ URL('farmer/restore/'.$farmer->id) }}" class="btn btn-sm btn-outline-success px-3" title="Restore Farmer">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>

                                        <form action="{{ URL('farmer/force-delete/'.$farmer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permanently? This cannot be undone!')">
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
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-trash fs-2"></i><br>
                                    No Trashed Farmer Records Found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $farmers->links() }}
    </div>
@endsection
