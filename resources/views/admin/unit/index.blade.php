@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Measurement Unit List</h3>

    <form action="{{URL("admin/unit")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by unit name or short name...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="mb-3">
        <x-button :url="URL('admin/unit/create')" type="primary">
            <i class="bi bi-plus-lg"></i> Add New Unit
        </x-button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color:#0ae264;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Unit Name</th>
                            <th scope="col">Short Name</th>
                            <th scope="col">Base Value (KG/Ltr)</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td><strong>{{ $unit->name }}</strong></td>
                                <td><code class="text-primary">{{ $unit->short_name }}</code></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info border border-info">
                                        {{ number_format($unit->base_unit_value, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ URL('admin/unit/edit/'.$unit->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('admin/unit/delete/'.$unit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this unit?')">
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
                                <td colspan="5" class="text-center py-5 text-danger">No Unit Records Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $units->appends(request()->query())->links() }}
    </div>
@endsection
