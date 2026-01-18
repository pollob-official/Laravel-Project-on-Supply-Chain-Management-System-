@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h2 class="text-success mb-2 mt-2">Measurement Unit List</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex gap-2">
                <x-button :url="URL('admin/unit/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> Add New Unit
                </x-button>

                {{-- ভবিষ্যতে সফট ডিলিট অ্যাড করতে চাইলে এখানে ট্রাশ বাটন দিতে পারেন --}}
                <a href="{{ URL('admin/unit/trashed') }}" class="btn btn-outline-danger d-none">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <form action="{{ URL('admin/unit') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by unit name or short name...">
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
                            <th scope="col" style="width: 80px;">#ID</th>
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
                                        <a href="{{ URL('admin/unit/edit/'.$unit->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('admin/unit/delete/'.$unit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this unit permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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

    <div class="mt-2">
        {{ $units->appends(request()->query())->links() }}
    </div>
@endsection
