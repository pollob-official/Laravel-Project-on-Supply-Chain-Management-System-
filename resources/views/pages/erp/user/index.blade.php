@extends("admin.layout.erp.app")
@section("content")

<table class="table">
    <thead style="background-color:#0ae264;">
        <tr>
            <th scope="col">#</th>
            <th scope="col">name</th>
            <th scope="col">email</th>
            <th scope="col">action</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($users as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                <td><a href="{{ URL("system/users/$user->id") }}">{{$user->name}}</a></td>
                <td>{{ $user->email }}</td>
                <td><a href="{{ URL("system/users/$user->id/edit") }}">Edit</a>

                <form action="{{ URL('system/users',$user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </td>
            </tr>


        @empty
            <tr>
                <th colspan="4"> Data Not Found</th>
            </tr>
        @endforelse
  </tbody>
</table>
@endsection
