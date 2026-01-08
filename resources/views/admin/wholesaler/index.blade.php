@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Wholesaler List</h3>

    <form action="{{URL("admin/wholesaler")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, phone or license...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <x-button :url="URL('admin/wholesaler/create')" type="primary">
        <i class="bi bi-plus-lg"></i> Add Wholesaler
    </x-button>
    <a href="{{ URL('admin/wholesaler/trashed') }}" class="btn btn-outline-danger">
        <i class="bi bi-trash"></i> View Trash
    </a>

    <table class="table mt-3 table-hover">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">#ID</th>
                <th scope="col">Wholesaler Details</th>
                <th scope="col">Trade License</th>
                <th scope="col">Warehouse Location</th>
                <th scope="col">Manpower</th>
                <th scope="col">Phone</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($wholesalers) > 0)
                @foreach ($wholesalers as $wholesaler)
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
                            <span class="badge bg-secondary">
                                {{ $wholesaler->wholesaler->total_manpower ?? 0 }} Persons
                            </span>
                        </td>
                        <td>{{ $wholesaler->phone }}</td>
                        <td>
                            <div class="d-flex gap-1">
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
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">No Wholesaler Data Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $wholesalers->appends(request()->query())->links() }}
    </div>
@endsection
