
@extends("layout.erp.app")
@section("content")
    <x-alert/>

    <h3>Event list</h3>

     <x-button :url="URL('event_type/create')" type="primary"><i class="bi bi-plus-lg"></i> Add Event</x-button>

   <table class="table">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">name</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($event_types as $event)
    <tr>
      <th scope="row">{{$event->id}}</th>
      <td>{{$event->name}}</td>



      <td class="btn btn-group">
         <a class="btn btn-secondary" href="{{URL("event_type/edit", $event->id)}}">Edit</a>

         <form action="{{URL("event_type/delete", $event->id)}}" method="post">
            @csrf
            @method("delete")
             <button onclick="return confirm(`Are you sure`)" type="submit" class="btn btn-danger">Delete</button>
          </form>


      </td>

    </tr>
    @endforeach
  </tbody>
</table>

{{-- <div class="">
    {{ $customers->links() }}
</div> --}}

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
 @endsection
