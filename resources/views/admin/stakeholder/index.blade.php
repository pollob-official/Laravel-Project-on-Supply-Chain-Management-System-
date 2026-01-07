@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Stakeholder List</h3>

    <form action="{{URL("stakeholder")}}" method="GET">
        <div class="mb-3  d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by name, phone or role...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <x-button :url="URL('stakeholder/create')" type="primary">
        <i class="bi bi-plus-lg"></i> Add Stakeholder
    </x-button>
    <a href="{{ URL('stakeholder/trashed') }}" class="btn btn-outline-danger">
    <i class="bi bi-trash"></i> View Trash
</a>

    <table class="table mt-3">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($stakeholders) > 0)
                @foreach ($stakeholders as $stakeholder)
                    <tr>
                        <td>{{ $stakeholder->id }}</td>
                        <td>{{ $stakeholder->name }}</td>
                        <td>
                            @php
                                $color = [
                                    'farmer' => 'success',
                                    'miller' => 'info',
                                    'wholesaler' => 'warning',
                                    'retailer' => 'primary'
                                ][$stakeholder->role] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{$color}}">{{ ucfirst($stakeholder->role) }}</span>
                        </td>
                        <td>{{ $stakeholder->email ?? 'N/A' }}</td>
                        <td>{{ $stakeholder->phone }}</td>
                        <td>{{ Str::limit($stakeholder->address, 30) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ URL('stakeholder/edit/'.$stakeholder->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ URL('stakeholder/delete/'.$stakeholder->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                    <td colspan="7" class="text-center text-danger">No Data Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $stakeholders->appends(request()->query())->links() }}
    </div>
@endsection
