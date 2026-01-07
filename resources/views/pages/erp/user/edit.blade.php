@extends("admin.layout.erp.app")
@section("content")

<form action="{{ URL('system/users', $user->id) }}" method="POST">
  @csrf
  @method("PUT")

  <table class="table table-bordered table-striped align-middle">
    <tbody>
      <tr>
        <th style="width: 200px;">Name</th>
        <td>
          <input
            type="text"
            name="name"
            value="{{ $user->name }}"
            class="form-control"
            required
          >
        </td>
      </tr>

      <tr>
        <th>Email Address</th>
        <td>
          <input
            type="email"
            name="email"
            value="{{ $user->email }}"
            class="form-control"
            required
          >
        </td>
      </tr>

      <tr>
        <th>Password</th>
        <td>
          <input
            type="password"
            name="password"
            class="form-control"
            placeholder="Leave blank to keep current password"
          >
        </td>
      </tr>

      <tr>
        <th></th>
        <td>
          <button type="submit" class="btn btn-primary">
            Update User
          </button>
          <a href="{{ url('system/users') }}" class="btn btn-secondary ms-2">
            Back
          </a>
        </td>
      </tr>
    </tbody>
  </table>
</form>
@endsection
