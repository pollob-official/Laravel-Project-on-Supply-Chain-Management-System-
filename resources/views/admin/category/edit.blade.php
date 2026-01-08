@extends("admin.layout.erp.app")
@section("content")

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Category: {{ $category->name }}</h3>
        <a href="{{ URL('admin/category') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ URL('admin/category/update/'.$category->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf
        @method('POST')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter category name" value="{{ old('name', $category->name) }}" required>
                @error("name") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Current Slug (Auto-updates)</label>
                <input type="text" class="form-control bg-white" value="{{ $category->slug }}" disabled>
                <small class="text-muted">Slug will be regenerated based on the name upon update.</small>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
            <select name="is_active" class="form-select form-control">
                <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Active (একটিভ)</option>
                <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>Inactive (ইন-অ্যাক্টিভ)</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label font-weight-bold">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Enter category description...">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-check-circle"></i> Update Category
            </button>
            <a href="{{ URL('admin/category') }}" class="btn btn-outline-secondary btn-lg px-4">Cancel</a>
        </div>
    </form>
</div>

@endsection
