@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Farmer List</h3>

    <form action="{{URL("farmer")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, phone, NID or card no...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="mb-3">
        <x-button :url="URL('farmer/create')" type="primary">
            <i class="bi bi-plus-lg"></i> Add Farmer
        </x-button>
        <a href="{{ URL('farmer/trashed') }}" class="btn btn-outline-danger">
            <i class="bi bi-trash"></i> View Trash
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-white" style="background-color:#0ae264;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Farmer Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Land Area</th>
                            <th scope="col">Card No</th>
                            <th scope="col">Crop History</th>
                            <th scope="col">Address</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($farmers as $farmer)
                            <tr>
                                <td>{{ $farmer->id }}</td>
                                <td>
                                    <strong>{{ $farmer->name }}</strong><br>
                                    <small class="text-muted">{{ $farmer->email ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $farmer->phone }}</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border">
                                        {{ $farmer->farmer->land_area ?? '0' }} Dec.
                                    </span>
                                </td>
                                <td>
                                    <code class="text-primary">
                                        {{ $farmer->farmer->farmer_card_no ?? 'N/A' }}
                                    </code>
                                </td>
                                <td>
                                    <small>{{ $farmer->farmer->crop_history ?? 'No Data' }}</small>
                                </td>
                                <td>{{ $farmer->address }}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ URL('farmer/edit/'.$farmer->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('farmer/delete/'.$farmer->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                                <td colspan="8" class="text-center py-5 text-danger">No Farmer Records Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $farmers->appends(request()->query())->links() }}
    </div>
@endsection
