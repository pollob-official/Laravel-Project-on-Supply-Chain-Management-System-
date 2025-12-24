  <div class="mb-3">
    <label class="form-label">{{$label}}</label>
    <input type="{{$type}}" name="{{$name}}" class="form-control" placeholder="Enter {{$name}}">
    @error($name)
        <span class="text-danger">{{$message}}</span>
    @enderror
  </div>
