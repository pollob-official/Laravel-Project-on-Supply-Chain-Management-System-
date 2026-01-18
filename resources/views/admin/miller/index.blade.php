@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h2 class="text-success mb-2 mt-2">Miller List</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex gap-2">
                <x-button :url="URL('admin/miller/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> Add Miller
                </x-button>

                <a href="{{ URL('admin/miller/trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <form action="{{ URL('admin/miller') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by name, phone, or license...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-white" style="background-color:#0ae264;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Miller Name</th>
                            <th scope="col">License</th>
                            <th scope="col">Capacity</th>
                            <th scope="col">Storage Type</th>
                            <th scope="col">Phone</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($millers as $miller)
                            <tr>
                                <td>{{ $miller->id }}</td>
                                <td>
                                    <strong>{{ $miller->name }}</strong><br>
                                    <small class="text-muted">{{ $miller->email ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <code class="text-primary">{{ $miller->miller->factory_license ?? 'N/A' }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border">
                                        {{ $miller->miller->milling_capacity ?? '0' }} Tons
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info border">
                                        {{ ucfirst($miller->miller->storage_unit_type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $miller->phone }}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ URL('admin/miller/edit/'.$miller->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('admin/miller/delete/'.$miller->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-danger">No Miller Records Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-2">
        {{ $millers->appends(request()->query())->links() }}
    </div>
@endsection
