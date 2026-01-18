@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h2 class="text-success mb-2 mt-2">Stakeholder List</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex gap-2">
                <x-button :url="URL('admin/stakeholder/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> Add Stakeholder
                </x-button>

                <a href="{{ URL('admin/stakeholder/trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <form action="{{ URL('admin/stakeholder') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by name, phone or role...">
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
                            <th scope="col">Stakeholder Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stakeholders as $stakeholder)
                            <tr>
                                <td>{{ $stakeholder->id }}</td>
                                <td>
                                    <strong>{{ $stakeholder->name }}</strong>
                                </td>
                                <td>
                                    @php
                                        $color = [
                                            'farmer' => 'success',
                                            'miller' => 'info',
                                            'wholesaler' => 'warning',
                                            'retailer' => 'primary'
                                        ][$stakeholder->role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{$color}}-subtle text-{{$color}} border border-{{$color}}">
                                        {{ ucfirst($stakeholder->role) }}
                                    </span>
                                </td>
                                <td>{{ $stakeholder->email ?? 'N/A' }}</td>
                                <td>{{ $stakeholder->phone }}</td>
                                <td>{{ Str::limit($stakeholder->address, 30) }}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ URL('admin/stakeholder/edit/'.$stakeholder->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('admin/stakeholder/delete/'.$stakeholder->id) }}" method="POST" onsubmit="return confirm('Move to trash?')">
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
                                <td colspan="7" class="text-center py-5 text-danger">No Stakeholder Records Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-2">
        {{ $stakeholders->appends(request()->query())->links() }}
    </div>
@endsection
