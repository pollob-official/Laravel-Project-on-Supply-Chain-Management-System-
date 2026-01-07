@extends("admin.layout.erp.app")
@section("content")

    <h1>Create Stakeholder</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

<form action="{{URL("stakeholder/save")}}" method="POST" class="p-4 border rounded shadow-sm bg-light">
   @csrf

  <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label font-weight-bold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{old('name')}}">
        @error("name")
            <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label font-weight-bold">Stakeholder Type <span class="text-danger">*</span></label>
        <select name="role" id="role_id" class="form-select form-control" onchange="showFields(this.value)">
            <option value="">Select Type</option>
            <option value="farmer">Farmer (কৃষক)</option>
            <option value="miller">Miller / Supplier (মিলার)</option>
            <option value="wholesaler">Wholesaler (পাইকার)</option>
            <option value="retailer">Retailer (খুচরা বিক্রেতা)</option>
        </select>
      </div>
  </div>

  <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{old('email')}}">
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Phone <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" placeholder="Enter phone" value="{{old('phone')}}">
      </div>
  </div>

  <div class="mb-3">
    <label class="form-label">NID Number</label>
    <input type="text" name="nid" class="form-control" placeholder="Enter NID Number">
  </div>

  <div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="2" placeholder="Enter full address"></textarea>
  </div>

  <div id="dynamic_fields" class="p-3 mb-3 border rounded bg-white" style="display:none;">

      <div id="farmer_div" class="specific_div" style="display:none;">
          <h5 class="text-success">Farmer Extra Info</h5>
          <div class="row">
              <div class="col-md-6"><input type="text" name="land_area" class="form-control" placeholder="Land Area (e.g. 2 Acre)"></div>
              <div class="col-md-6"><input type="text" name="farmer_card_no" class="form-control" placeholder="Farmer Card No"></div>
          </div>
      </div>

      <div id="miller_div" class="specific_div" style="display:none;">
          <h5 class="text-info">Miller Extra Info</h5>
          <input type="text" name="factory_license" class="form-control" placeholder="Factory License Number">
      </div>

      <div id="wholesaler_div" class="specific_div" style="display:none;">
          <h5 class="text-warning text-dark">Wholesaler Extra Info</h5>
          <input type="text" name="trade_license" class="form-control" placeholder="Trade License Number">
      </div>

      <div id="retailer_div" class="specific_div" style="display:none;">
          <h5 class="text-primary">Retailer Extra Info</h5>
          <input type="text" name="shop_name" class="form-control" placeholder="Shop Name">
      </div>

  </div>

  <div class="mt-4">
      <button type="submit" class="btn btn-primary btn-lg">
        Save Stakeholder
      </button>
      <a href="{{URL('stakeholder')}}" class="btn btn-secondary btn-lg">Cancel</a>
  </div>

</form>

<script>
    function showFields(role){
        // প্রথমে সব ডিভ হাইড করো
        document.getElementById('dynamic_fields').style.display = role ? 'block' : 'none';
        let divs = document.getElementsByClassName('specific_div');
        for(let i=0; i<divs.length; i++) divs[i].style.display = 'none';

        // রোল অনুযায়ী ডিভ দেখাও
        if(role == 'farmer') document.getElementById('farmer_div').style.display = 'block';
        if(role == 'miller') document.getElementById('miller_div').style.display = 'block';
        if(role == 'wholesaler') document.getElementById('wholesaler_div').style.display = 'block';
        if(role == 'retailer') document.getElementById('retailer_div').style.display = 'block';
    }
</script>

@endsection
