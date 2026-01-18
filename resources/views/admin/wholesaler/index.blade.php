@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h2 class="text-success mb-2 mt-2">Wholesaler List</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex gap-2">
                <x-button :url="URL('admin/wholesaler/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> Add Wholesaler
                </x-button>

                <a href="{{ URL('admin/wholesaler/trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <form action="{{ URL('admin/wholesaler') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by name, phone or license...">
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
                            <th scope="col">Wholesaler Details</th>
                            <th scope="col">Trade License</th>
                            <th scope="col">Warehouse Location</th>
                            <th scope="col">Manpower</th>
                            <th scope="col">Phone</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wholesalers as $wholesaler)
                            <tr>
                                <td>{{ $wholesaler->id }}</td>
                                <td>
                                    <strong>{{ $wholesaler->name }}</strong><br>
                                    <small class="text-muted">{{ $wholesaler->email ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning-subtle text-dark border border-warning">
                                        {{ $wholesaler->wholesaler->trade_license ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $wholesaler->wholesaler->warehouse_location ?? 'Not Set' }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        {{ $wholesaler->wholesaler->total_manpower ?? 0 }} Persons
                                    </span>
                                </td>
                                <td>{{ $wholesaler->phone }}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ URL('admin/wholesaler/edit/'.$wholesaler->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('admin/wholesaler/delete/'.$wholesaler->id) }}" method="POST" onsubmit="return confirm('Move to trash?')">
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
                                <td colspan="7" class="text-center py-5 text-danger">No Wholesaler Records Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-2">
        {{ $wholesalers->appends(request()->query())->links() }}
    </div>
@endsection
