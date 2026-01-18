@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <h2 class="text-success mb-2 mt-2">Product Category List</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex gap-2">
                <x-button :url="URL('admin/category/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> Add New Category
                </x-button>

                {{-- যেহেতু সফট ডিলিট ফাংশন নেই, আপনি চাইলে বাটনটি হাইড রাখতে পারেন বা পরে এড করতে পারেন --}}
                {{-- <a href="{{ URL('admin/category/trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> View Trash
                </a> --}}
            </div>

            <form action="{{ URL('admin/category') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by name or slug...">
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
                            <th scope="col">Category Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                                <td>
                                    <code class="text-primary">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($category->description, 50) ?? 'No description provided' }}
                                    </small>
                                </td>
                                <td>
                                    @if($category->is_active == 1)
                                        <span class="badge bg-success-subtle text-success border border-success">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ URL('admin/category/edit/'.$category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ URL('admin/category/delete/'.$category->id) }}" method="POST" onsubmit="return confirm('Are you sure? Permanent delete!')">
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
                                <td colspan="6" class="text-center py-5 text-danger">No Category Records Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-2">
        {{ $categories->appends(request()->query())->links() }}
    </div>
@endsection
