@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h1>Stakeholder List</h1>

    <form action="{{URL("stakeholder")}}" method="GET">
        <div class="mb-3">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search stakeholders">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>


    <x-button :url="URL('stakeholder/create')" type="primary">
        <i class="bi bi-plus-lg"></i> Add Stakeholder
    </x-button>

    <table class="table">
        <thead>
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
            @foreach ($stakeholders as $stakeholder)
                <tr>
                    <td>{{ $stakeholder->id }}</td>
                    <td>{{ $stakeholder->name }}</td>
                    <td>
                        {{-- রোল অনুযায়ী ছোট ব্যাজ --}}
                        <span class="badge {{ $stakeholder->role == 'farmer' ? 'bg-success' : 'bg-info' }}">
                            {{ ucfirst($stakeholder->role) }}
                        </span>
                    </td>
                    <td>{{ $stakeholder->email }}</td>
                    <td>{{ $stakeholder->phone }}</td>
                    <td>{{ $stakeholder->address }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ URL('stakeholder/edit/'.$stakeholder->id) }}" class="btn btn-sm btn-info me-1">Edit</a>

                            <form action="{{ URL('stakeholder/delete/'.$stakeholder->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="">
        {{ $stakeholders->appends(request()->query())->links() }}
    </div>
@endsection
