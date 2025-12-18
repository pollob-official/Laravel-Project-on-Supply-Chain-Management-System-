

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Customer</title>
  </head>
  <body>

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
    <input type="text" name="name" class="form-control" placeholder="Enter name">
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

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
