@extends("layout.erp.app")
@section("content")
    <x-alert/>

  <h1 class="mb-2">Farmer List</h1>

    <form action="{{URL("farmer")}}" method="GET">
        <div class="mb-3 ">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, phone or role...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <x-button :url="URL('farmer/create')" type="primary">
        <i class="bi bi-plus-lg"></i> Add Farmer
    </x-button>
    <a href="{{ URL('farmer/trashed') }}" class="btn btn-outline-danger">
    <i class="bi bi-trash"></i> View Trash
</a>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Farmer Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Land Area</th>
                        <th scope="col">Card No</th>
                        <th scope="col">Address</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($farmers) > 0)
                        @foreach ($farmers as $farmer)
                            <tr>
                                <td>{{ $farmer->id }}</td>
                                <td>
                                    <strong>{{ $farmer->name }}</strong><br>
                                    <small class="text-muted">{{ $farmer->email ?? 'no-email@scms.com' }}</small>
                                </td>
                                <td>{{ $farmer->phone }}</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ $farmer->farmer->land_area ?? '0' }} Decimal
                                    </span>
                                </td>
                                <td>
                                    <code class="text-primary font-weight-bold">
                                        {{ $farmer->farmer->farmer_card_no ?? 'N/A' }}
                                    </code>
                                </td>
                                <td>{{ Str::limit($farmer->address, 25) }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ URL('farmer/edit/'.$farmer->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Profile">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ URL('farmer/delete/'.$farmer->id) }}" method="POST" onsubmit="return confirm('Move this farmer to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Move to Trash">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fs-2"></i><br>
                                No Farmer Records Found
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $farmers->appends(request()->query())->links() }}
    </div>
@endsection
