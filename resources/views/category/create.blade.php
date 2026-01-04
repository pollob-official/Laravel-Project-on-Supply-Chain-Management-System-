@extends("layout.erp.app")
@section("content")

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Add New Category</h3>
        <a href="{{ URL('category') }}" class="btn btn-secondary shadow-sm">
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

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 bg-light">
            <form action="{{ URL('category/store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label font-weight-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. Fine Rice, Lentils, Spices" value="{{ old('name') }}" required>
                        @error("name")
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                        <select name="is_active" class="form-select form-control">
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active (একটিভ)</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive (ইন-অ্যাক্টিভ)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Description</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Enter some details about this category (Optional)...">{{ old('description') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="bi bi-save"></i> Save Category
                    </button>
                    <button type="reset" class="btn btn-outline-dark btn-lg px-4">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
