
@extends("admin.layout.erp.app")
@section("content")


<form action="{{URL("customer/update", $customer->id)}}" method="POST" enctype="multipart/form-data" class="p-4 border rounded">
   @csrf
  <!-- Name -->
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input value="{{$customer->name}}"  type="text" name="name" class="form-control" placeholder="Enter name">
  </div>

  <!-- Email -->
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input value="{{$customer->email}}" type="email" name="email" class="form-control" placeholder="Enter email">
  </div>

  <!-- Phone -->
  <div class="mb-3">
    <label class="form-label">Phone</label>
    <input value="{{$customer->phone}}" type="text" name="phone" class="form-control" placeholder="Enter phone">
  </div>

  <!-- Address -->
  <div class="mb-3">
    <label class="form-label">Address</label>
    <textarea  name="address" class="form-control" rows="3" placeholder="Enter address"> {{$customer->address}}</textarea>
  </div>


  <div class="mb-3">
    <label class="form-label">Photo</label>
    <input type="file" name="photo" class="form-control">
  </div>

  <!-- Action -->
  <button type="submit" class="btn btn-primary">
    Update Customer
  </button>

</form>

@endsection
