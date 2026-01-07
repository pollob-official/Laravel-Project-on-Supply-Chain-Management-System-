
@extends("admin.layout.erp.app")
@section("content")


    <h1>Create Customers</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Create Post Form -->

<form action="{{URL("customer/save")}}" method="POST" enctype="multipart/form-data" class="p-4 border rounded">
   @csrf
  <!-- Name -->
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{old('name')}}">
    @error("name")
        <span class="text-danger">{{$message}}</span>
    @enderror
  </div>

  <!-- Email -->
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" placeholder="Enter email">
     @error("email")
        <span class="text-danger">{{$message}}</span>
    @enderror
  </div>

  <!-- Phone -->
  <div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" placeholder="Enter phone">
  </div>

  <!-- Address -->
  <div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="3" placeholder="Enter address"></textarea>
  </div>

  <!-- Photo -->
  <div class="mb-3">
    <label class="form-label">Photo</label>
    <input type="file" name="photo" class="form-control">
  </div>

  <!-- Action -->
  <button type="submit" class="btn btn-primary">
    Save Customer
  </button>

</form>

  @endsection
