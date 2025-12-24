<x-button :url="URL('users/create')" type="secondary">Create Student</x-button>


<table class="table">
    <thead>
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
                <td> <a href="{{ URL("system/users/$user->id") }}"> {{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route("user.restore", $user->id) }}">Restore</a>

                    <form action="{{ route('user.delete', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <th colspan="4">Data Not Found</th>
            </tr>
        @endforelse


    </tbody>
</table>
