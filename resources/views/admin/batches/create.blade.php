@extends("admin.layout.erp.app")
@section("content")

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-primary"><i class="bi bi-qr-code-scan"></i> Generate Smart Product Batch</h3>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Batch List
        </a>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ URL('admin/batches/store') }}" method="POST" class="p-4 border rounded shadow-sm bg-light">
        @csrf

        {{-- Section 1: Identification --}}
        <h4 class="text-muted mb-3 border-bottom pb-2">Batch Identification & Farmer Info</h4>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select form-control" required>
                    <option value="">-- Choose Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Source Farmer <span class="text-danger">*</span></label>
                <select name="initial_farmer_id" class="form-select form-control" required>
                    <option value="">-- Choose Farmer --</option>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}">{{ $farmer->name }} ({{ $farmer->phone }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold">Current Stage/Location</label>
                <input type="text" name="current_location" class="form-control" value="Farmer Field" placeholder="e.g. Processing Center">
            </div>
        </div>

        {{-- Section 2: Traceability (Smart Info) --}}
        <h4 class="text-info mb-3 border-bottom pb-2"><i class="bi bi-seedling"></i> Seed & Cultivation Info</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold">Seed Brand</label>
                <input type="text" name="seed_brand" class="form-control border-info" placeholder="e.g. Bayer / Syngenta">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold">Seed Variety</label>
                <input type="text" name="seed_variety" class="form-control border-info" placeholder="e.g. Miniket / BRRI-28">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger">Sowing Date</label>
                <input type="date" name="sowing_date" class="form-control border-danger">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label font-weight-bold text-danger">Last Pesticide Date</label>
                <input type="date" name="last_pesticide_date" class="form-control border-danger">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Fertilizer History</label>
                <textarea name="fertilizer_history" class="form-control" rows="2" placeholder="Describe fertilizer usage..."></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label font-weight-bold">Pesticide History</label>
                <textarea name="pesticide_history" class="form-control" rows="2" placeholder="Describe pesticide usage..."></textarea>
            </div>
        </div>

        {{-- Section 3: Quantity & Safety --}}
        <h4 class="text-success mb-3 border-bottom pb-2"><i class="bi bi-box-seam"></i> Quantity & Quality Analysis</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Total Quantity (KG/Unit)</label>
                <input type="number" step="0.01" name="total_quantity" class="form-control border-success" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Moisture Level (%)</label>
                <input type="text" name="moisture_level" class="form-control border-success" placeholder="e.g. 12%">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Manufacturing Date</label>
                <input type="date" name="manufacturing_date" class="form-control border-success" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label text-success font-weight-bold">Harvest Date</label>
                <input type="date" name="harvest_date" class="form-control border-success">
            </div>

            {{-- Hidden Geolocation Fields (Auto Capture) --}}
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lon">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label font-weight-bold text-secondary">Expiry Date (Optional)</label>
                <input type="date" name="expiry_date" class="form-control border-secondary">
            </div>
            <div class="col-md-8 mb-3">
                <div class="alert alert-warning py-2 small mt-4 mb-0">
                    <i class="bi bi-geo-alt"></i> <strong>Geolocation:</strong> System will automatically tag current coordinates for traceability map.
                </div>
            </div>
        </div>

        <div class="mt-3 border-top pt-3">
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bi bi-qr-code"></i> Generate Smart Batch & QR
            </button>
            <button type="reset" class="btn btn-outline-secondary px-3">Clear Form</button>
        </div>
    </form>
</div>

<script>
    // Geolocation Capture
    window.onload = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lon').value = position.coords.longitude;
            });
        }
    }
</script>

@endsection
