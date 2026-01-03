@extends("layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Stakeholder ({{ ucfirst($stakeholder->role) }})</h3>
        <a href="{{ URL('stakeholder') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <form action="{{ URL('stakeholder/update', $stakeholder->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Name</label>
                <input value="{{ $stakeholder->name }}" type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input value="{{ $stakeholder->email }}" type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Phone</label>
                <input value="{{ $stakeholder->phone }}" type="text" name="phone" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">NID Number</label>
                <input value="{{ $stakeholder->nid }}" type="text" name="nid" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label font-weight-bold">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $stakeholder->address }}</textarea>
            </div>
           
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Role</label>
                <select name="role" class="form-select" disabled> {{-- রোল সাধারণত এডিট করতে দেয়া হয় না --}}
                    <option value="farmer" {{ strtolower($stakeholder->role) == 'farmer' ? 'selected' : '' }}>Farmer</option>
                    <option value="miller" {{ strtolower($stakeholder->role) == 'miller' ? 'selected' : '' }}>Miller</option>
                    <option value="wholesaler" {{ strtolower($stakeholder->role) == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                    <option value="retailer" {{ strtolower($stakeholder->role) == 'retailer' ? 'selected' : '' }}>Retailer</option>
                </select>
                <small class="text-muted">Role cannot be changed during update.</small>
            </div>

            <hr>

            @if($stakeholder->role == 'farmer')
                <div class="col-md-6 mb-3">
                    <label class="form-label text-success font-weight-bold">Land Area (Acre)</label>
                    <input value="{{ $stakeholder->farmer->land_area ?? '' }}" type="text" name="land_area" class="form-control border-success">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-success font-weight-bold">Farmer Card No</label>
                    <input value="{{ $stakeholder->farmer->farmer_card_no ?? '' }}" type="text" name="farmer_card_no" class="form-control border-success">
                </div>

            @elseif($stakeholder->role == 'miller')
                <div class="col-md-12 mb-3">
                    <label class="form-label text-info font-weight-bold">Factory License Number</label>
                    <input value="{{ $stakeholder->miller->factory_license ?? '' }}" type="text" name="factory_license" class="form-control border-info">
                </div>

            @elseif($stakeholder->role == 'wholesaler')
                <div class="col-md-12 mb-3">
                    <label class="form-label text-warning font-weight-bold">Trade License Number</label>
                    <input value="{{ $stakeholder->wholesaler->trade_license ?? '' }}" type="text" name="trade_license" class="form-control border-warning">
                </div>

            @elseif($stakeholder->role == 'retailer')
                <div class="col-md-12 mb-3">
                    <label class="form-label text-primary font-weight-bold">Shop Name</label>
                    <input value="{{ $stakeholder->retailer->shop_name ?? '' }}" type="text" name="shop_name" class="form-control border-primary">
                </div>
            @endif
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-5">
                <i class="bi bi-save"></i> Update Stakeholder Info
            </button>
        </div>
    </form>
</div>

@endsection
