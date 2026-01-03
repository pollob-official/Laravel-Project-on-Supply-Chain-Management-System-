
@extends("layout.erp.app")
@section("content")
    <x-alert/>

<h1>Customer List</h1>

  <form action="{{URL("customer")}}" method="GET">
        <div class="mb-3">
            <input value="{{request("search")}}" type="text" class="form-control" id="search" name="search" placeholder="Search data">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>




    <x-button :url="URL('customer/create')" type="primary"><i class="bi bi-plus-lg"></i> Add Customer</x-button>
    {{-- <span>
        <a href="{{ url('customer/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Customer
        </a>
    </span> --}}

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Photo</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <x-customertable :customer="$customer" />
            @endforeach
        </tbody>
    </table>

    <div class="">
        {{ $customers->appends(request()->query())->links() }}
    </div>
@endsection
