@extends("layout.erp.app")
@section("content")

    <h1>Create Farmer</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

<form action="{{ url('farmer/store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>NID Number</label>
            <input type="text" name="nid" class="form-control">
        </div>
        <div class="col-md-12">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="col-md-6">
            <label>Land Area (Decimal/Acre)</label>
            <input type="text" name="land_area" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Farmer Card No</label>
            <input type="text" name="farmer_card_no" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save Farmer</button>
</form>

@endsection
