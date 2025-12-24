
@props(['customer'])
<tr>
      <th scope="row">{{$customer->id}}</th>
      <td>{{$customer->name}}</td>
      <td>{{$customer->email}}</td>
      <td>{{$customer->phone}}</td>
      <td>{{$customer->address}}</td>

      {{-- <td> <img src="{{asset("storage" )}}/{{$customer->photo}}" alt="" srcset="" width="100">       </td> --}}
      <td> <img src="{{asset("storage/photo/customer" )}}/{{$customer->photo}}" alt="" srcset="" width="100">       </td>
      <td class="btn btn-group">
         <a class="btn btn-secondary" href="{{URL("customer/edit", $customer->id)}}">Edit</a>

         <form action="{{URL("customer/delete", $customer->id)}}" method="post">
            @csrf
            @method("delete")
             <button onclick="return confirm(`Are you sure`)" type="submit" class="btn btn-danger">Delete</button>
         </form>


    </td>

    </tr>
