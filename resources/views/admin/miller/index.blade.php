@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Miller List</h3>

    <form action="{{URL("admin/miller")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, phone, or license...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <x-button :url="URL('admin/miller/create')" type="primary">
        <i class="bi bi-plus-lg"></i> Add Miller
    </x-button>
    <a href="{{ URL('admin/miller/trashed') }}" class="btn btn-outline-danger">
        <i class="bi bi-trash"></i> View Trash
    </a>

    <table class="table mt-3 table-hover">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">#ID</th>
                <th scope="col">Miller Name</th>
                <th scope="col">License</th>
                <th scope="col">Capacity</th>
                <th scope="col">Storage Type</th>
                <th scope="col">Phone</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($millers) > 0)
                @foreach ($millers as $miller)
                    <tr>
                        <td>{{ $miller->id }}</td>
                        <td>
                            <strong>{{ $miller->name }}</strong><br>
                            <small class="text-muted">{{ $miller->email ?? 'N/A' }}</small>
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
                        <td>{{ $miller->phone }}</td>
                        <td>
                            <div class="d-flex gap-1">
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
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">No Miller Data Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $millers->appends(request()->query())->links() }}
    </div>
@endsection
