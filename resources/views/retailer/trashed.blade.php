@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Retailer Trash List</h3>

    <form action="{{URL("retailer/trashed")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search in trash by name, phone or shop...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <a href="{{ URL('retailer') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>

    <table class="table mt-3 table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">#ID</th>
                <th scope="col">Retailer Details</th>
                <th scope="col">Shop Name</th>
                <th scope="col">Market Name</th>
                <th scope="col">Deleted At</th>
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
                            <small class="text-muted">{{ $retailer->phone }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary">
                                {{ $retailer->retailer->shop_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $retailer->retailer->market_name ?? 'N/A' }}</td>
                        <td>
                            <span class="text-danger small">
                                {{ $retailer->deleted_at->format('d M, Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- আপনার রাউট অনুযায়ী Restore (GET) --}}
                                <a href="{{ URL('retailer/restore/'.$retailer->id) }}" class="btn btn-sm btn-outline-success" title="Restore">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                </a>

                                {{-- আপনার রাউট অনুযায়ী Force Delete (GET) --}}
                                <a href="{{ URL('retailer/force-delete/'.$retailer->id) }}"
                                   class="btn btn-sm btn-outline-danger"
                                   title="Permanent Delete"
                                   onclick="return confirm('Are you sure you want to delete this permanently?')">
                                    <i class="bi bi-x-circle"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">No Trashed Data Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $retailers->appends(request()->query())->links() }}
    </div>
@endsection
