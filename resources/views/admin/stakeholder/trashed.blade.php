@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Trashed Stakeholders</h3>
        <div>
            <a href="{{ URL('admin/stakeholder') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">#ID</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>
                <th scope="col">Phone</th>
                <th scope="col">Deleted At</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stakeholders as $stakeholder)
                <tr>
                    <td>{{ $stakeholder->id }}</td>
                    <td>{{ $stakeholder->name }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ ucfirst($stakeholder->role) }}</span>
                    </td>
                    <td>{{ $stakeholder->phone }}</td>
                    <td>{{ $stakeholder->deleted_at->format('d M, Y h:i A') }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ URL('admin/stakeholder/restore/'.$stakeholder->id) }}" class="btn btn-sm btn-success">
                                Restore
                            </a>

                            <form action="{{ URL('admin/stakeholder/force-delete/'.$stakeholder->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No trashed records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $stakeholders->links() }}
    </div>
@endsection
