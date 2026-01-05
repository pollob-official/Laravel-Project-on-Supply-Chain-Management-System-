@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Retailer List</h3>

    <form action="{{URL("retailer")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, phone, shop or market...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <x-button :url="URL('retailer/create')" type="primary">
        <i class="bi bi-plus-lg"></i> Add Retailer
    </x-button>
    <a href="{{ URL('retailer/trashed') }}" class="btn btn-outline-danger">
        <i class="bi bi-trash"></i> View Trash
    </a>

    <table class="table mt-3 table-hover">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">#ID</th>
                <th scope="col">Retailer Details</th>
                <th scope="col">Shop Name</th>
                <th scope="col">Market Name</th>
                <th scope="col">TIN No</th>
                <th scope="col">Phone</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($retailers) > 0)
                @foreach ($retailers as $retailer)
                    <tr>
                        <td>{{ $retailer->id }}</td>
                        <td>
                            <strong>{{ $retailer->name }}</strong><br>
                            <small class="text-muted">{{ $retailer->email ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary">
                                {{ $retailer->retailer->shop_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $retailer->retailer->market_name ?? 'Not Set' }}</td>
                        <td>
                            <code class="text-dark">{{ $retailer->retailer->tin_no ?? 'N/A' }}</code>
                        </td>
                        <td>{{ $retailer->phone }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ URL('retailer/edit/'.$retailer->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ URL('retailer/delete/'.$retailer->id) }}" method="POST" onsubmit="return confirm('Move to trash?')">
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
                    <td colspan="7" class="text-center text-danger py-4">No Retailer Data Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $retailers->appends(request()->query())->links() }}
    </div>
@endsection
