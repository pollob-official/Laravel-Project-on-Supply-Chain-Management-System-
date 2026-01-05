@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h3 class="mb-2">Product Category List</h3>

    <form action="{{URL("category")}}" method="GET">
        <div class="mb-3 d-flex gap-2">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search by category name or slug...">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="mb-3">
        <x-button :url="URL('category/create')" type="primary">
            <i class="bi bi-plus-lg"></i> Add New Category
        </x-button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color:#0ae264;">
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
                                        <a href="{{ URL('category/edit/'.$category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- সরাসরি ডিলিট লজিক (যেহেতু সফট ডিলিট নেই) --}}
                                        <form action="{{ URL('category/delete/'.$category->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete the category permanently.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Permanent">
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

    <div class="mt-3">
        {{ $categories->appends(request()->query())->links() }}
    </div>
@endsection
