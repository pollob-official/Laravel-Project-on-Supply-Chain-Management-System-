@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-primary"><i class="bi bi-qr-code-scan"></i> Generate Smart Product Batch</h3>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Batch List
        </a>
    </div>

    <form action="{{ URL('admin/batches/store') }}" method="POST" class="p-4 border rounded shadow-sm bg-white">
        @csrf

        {{-- Section 1: Identification & GPS --}}
        <h4 class="text-muted mb-3 border-bottom pb-2">Batch Identification & Farm GPS</h4>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                    <option value="">-- Choose Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Source Farmer <span class="text-danger">*</span></label>
                <select name="initial_farmer_id" class="form-select @error('initial_farmer_id') is-invalid @enderror" required>
                    <option value="">-- Choose Farmer --</option>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}" {{ old('initial_farmer_id') == $farmer->id ? 'selected' : '' }}>{{ $farmer->name }} ({{ $farmer->phone }})</option>
                    @endforeach
                </select>
                @error('initial_farmer_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Current Stage/Location</label>
                <input type="text" name="current_location" id="current_location" class="form-control" value="{{ old('current_location', 'Farmer Field') }}">
            </div>
        </div>

        {{-- GPS Coordinates Auto-Capture --}}
        <div class="row bg-light p-3 rounded mb-4 mx-1 border">
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold text-primary"><i class="bi bi-geo"></i> Latitude (অক্ষরেখা)</label>
                <input type="text" name="latitude" id="lat" class="form-control border-primary" readonly placeholder="Detecting...">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label font-weight-bold text-primary"><i class="bi bi-geo"></i> Longitude (দ্রাঘিমারেখা)</label>
                <input type="text" name="longitude" id="lon" class="form-control border-primary" readonly placeholder="Detecting...">
            </div>
            <div class="col-12">
                <small id="gps_msg" class="text-muted"><i class="bi bi-broadcast"></i> GPS tracking is active for farm verification.</small>
            </div>
        </div>

        {{-- Section 2: Cultivation & Seed Info --}}
        <h4 class="text-info mb-3 border-bottom pb-2 mt-2"><i class="bi bi-seedling"></i> Seed & Cultivation Info</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold small">Seed Brand</label>
                <input type="text" name="seed_brand" class="form-control border-info" value="{{ old('seed_brand') }}" placeholder="e.g. Bayer">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold small">Seed Variety</label>
                <input type="text" name="seed_variety" class="form-control border-info" value="{{ old('seed_variety') }}" placeholder="e.g. Miniket">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger small">Sowing Date</label>
                <input type="date" name="sowing_date" class="form-control border-danger" value="{{ old('sowing_date') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger small">Last Pesticide Date</label>
                <input type="date" name="last_pesticide_date" class="form-control border-danger" value="{{ old('last_pesticide_date') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Fertilizer History</label>
                <textarea name="fertilizer_history" class="form-control" rows="2" placeholder="Describe fertilizers used...">{{ old('fertilizer_history') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Pesticide History</label>
                <textarea name="pesticide_history" class="form-control" rows="2" placeholder="Describe pesticides used...">{{ old('pesticide_history') }}</textarea>
            </div>
        </div>

        {{-- Section 3: Quantity & Safety --}}
        <h4 class="text-success mb-3 border-bottom pb-2 mt-2"><i class="bi bi-box-seam"></i> Quantity & Quality Analysis</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Total Quantity (KG/Unit) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="total_quantity" class="form-control border-success @error('total_quantity') is-invalid @enderror" value="{{ old('total_quantity') }}" required>
                @error('total_quantity') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold small">Moisture Level (%)</label>
                <input type="text" name="moisture_level" class="form-control border-success" value="{{ old('moisture_level', '12%') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Manufacturing Date <span class="text-danger">*</span></label>
                <input type="date" name="manufacturing_date" class="form-control border-success" value="{{ old('manufacturing_date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold small">Harvest Date</label>
                <input type="date" name="harvest_date" class="form-control border-success" value="{{ old('harvest_date') }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-secondary">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control border-secondary" value="{{ old('expiry_date') }}">
            </div>
            <div class="col-md-8 mb-3 d-flex align-items-end">
                <div class="alert alert-info py-2 small w-100 mb-0 shadow-sm border-info">
                    <i class="bi bi-info-circle-fill text-primary"></i> <strong>Note:</strong> QR Code will be generated automatically upon submission.
                </div>
            </div>
        </div>

        <div class="mt-4 border-top pt-3 text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-qr-code"></i> Create Batch & Generate QR
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg px-3">Reset Form</button>
        </div>
    </form>
</div>

<script>
    // Geolocation Auto-Capture Logic (World Class Standard)
    window.onload = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lat').value = position.coords.latitude.toFixed(6);
                document.getElementById('lon').value = position.coords.longitude.toFixed(6);
                document.getElementById('gps_msg').innerHTML = "<i class='bi bi-check-circle-fill text-success'></i> Farm Location Locked: " + position.coords.latitude.toFixed(4) + ", " + position.coords.longitude.toFixed(4);
            }, function(error) {
                document.getElementById('gps_msg').innerHTML = "<i class='bi bi-exclamation-triangle-fill text-danger'></i> GPS Error: Please enable location for authenticity.";
            });
        }
    };
</script>

@endsection
