
@extends("layout.erp.app")
@section("content")
    <x-alert/>

<h3>Customer List</h3>

  <a href="{{ url('customer/create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Add Customer
  </a>
</span>
   <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">name</th>
      <th scope="col">email</th>
      <th scope="col">phone</th>
      <th scope="col">address</th>
      <th scope="col">photo</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($customers as $customer)
    <tr>
      <th scope="row">{{$customer->id}}</th>
      <td>{{$customer->name}}</td>
      <td>{{$customer->email}}</td>
      <td>{{$customer->phone}}</td>
      <td>{{$customer->address}}</td>



      {{-- <td> <img src="{{asset("storage" )}}/{{$customer->photo}}" alt="" srcset="" width="100">       </td> --}}
     <td> <img src="{{asset("storage/photo/customer" )}}/{{$customer->photo}}" alt="" srcset="" width="50" height="50">       </td>
      <td class="btn btn-group">
         <a class="btn btn-secondary" href="{{URL("customer/edit", $customer->id)}}">Edit</a>

         <form action="{{URL("customer/delete", $customer->id)}}" method="post">
            @csrf
            @method("delete")
             <button onclick="return confirm(`Are you sure`)" type="submit" class="btn btn-danger">Delete</button>
          </form>


      </td>

    </tr>
    @endforeach
  </tbody>
</table>

<div class="">
    {{ $customers->links() }}
</div>




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
                <th scope="col">name</th>
                <th scope="col">email</th>
                <th scope="col">phone</th>
                <th scope="col">address</th>
                <th scope="col">photo</th>
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
