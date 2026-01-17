@extends('admin.layout.erp.app')
@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="text-primary fw-bold"><i class="bi bi-qr-code-scan"></i> Generate World-Class Product Batch</h3>
            <a href="{{ URL('admin/batches') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="bi bi-arrow-left"></i> Back to Batch List
            </a>
        </div>

        <form action="{{ URL('admin/batches/store') }}" method="POST" class="p-4 border-0 rounded-4 shadow bg-white">
            @csrf

            {{-- Section 1: Identification & GPS --}}
            <h4 class="text-muted mb-3 border-bottom pb-2">Batch Identification & Farm GPS</h4>


            <div class="row">
                {{-- Select Product Section --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror shadow-sm"
                            required>
                            <option value="">-- Choose Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        {{-- Add Product Button --}}
                        <x-button :url="URL('admin/product/create')" type="primary">
                            <i class="bi bi-plus-lg"></i>
                        </x-button>
                    </div>
                    @error('product_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Select Farmer Section --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Source Farmer <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <select name="initial_farmer_id"
                            class="form-select @error('initial_farmer_id') is-invalid @enderror shadow-sm" required>
                            <option value="">-- Choose Farmer --</option>
                            @foreach ($farmers as $farmer)
                                <option value="{{ $farmer->id }}"
                                    {{ old('initial_farmer_id') == $farmer->id ? 'selected' : '' }}>{{ $farmer->name }}
                                    ({{ $farmer->phone }})</option>
                            @endforeach
                        </select>
                        {{-- Add Farmer Button --}}
                        <x-button :url="URL('admin/farmer/create')" type="primary">
                            <i class="bi bi-plus-lg"></i>
                        </x-button>
                    </div>
                    @error('initial_farmer_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Current Location --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Current Stage/Location</label>
                    <input type="text" name="current_location" id="current_location" class="form-control shadow-sm"
                        value="{{ old('current_location', 'Farmer Field') }}">
                </div>
            </div>

            {{-- GPS Coordinates Section --}}
            <div class="row bg-light p-3 rounded-4 mb-4 mx-1 border border-primary border-opacity-25">
                <div class="col-md-6 mb-2">
                    <label class="form-label font-weight-bold text-primary small"><i class="bi bi-geo-alt-fill"></i>
                        Latitude (অক্ষরেখা)</label>
                    <input type="text" name="latitude" id="lat"
                        class="form-control border-primary bg-white shadow-sm" readonly placeholder="Detecting...">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label font-weight-bold text-primary small"><i class="bi bi-geo-alt-fill"></i>
                        Longitude (দ্রাঘিমারেখা)</label>
                    <input type="text" name="longitude" id="lon"
                        class="form-control border-primary bg-white shadow-sm" readonly placeholder="Detecting...">
                </div>
                <div class="col-12 mt-1">
                    <small id="gps_msg" class="text-muted fw-semibold"><i class="bi bi-broadcast"></i> Securely capturing
                        farm location for traceability...</small>
                </div>
            </div>


            {{-- Section 2: Cultivation & Seed Info --}}
            <h4 class="text-info mb-3 border-bottom pb-2 mt-2"><i class="bi bi-seedling"></i> Seed & Cultivation Info</h4>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold small text-muted">Seed Brand</label>
                    <input type="text" name="seed_brand" class="form-control border-info shadow-sm"
                        value="{{ old('seed_brand') }}" placeholder="e.g. Bayer">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold small text-muted">Seed Variety</label>
                    <input type="text" name="seed_variety" class="form-control border-info shadow-sm"
                        value="{{ old('seed_variety') }}" placeholder="e.g. Miniket">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold text-danger small">Sowing Date</label>
                    <input type="date" name="sowing_date" class="form-control border-danger shadow-sm"
                        value="{{ old('sowing_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold text-danger small">Last Pesticide Date</label>
                    <input type="date" name="last_pesticide_date" class="form-control border-danger shadow-sm"
                        value="{{ old('last_pesticide_date') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-muted small">Fertilizer History</label>
                    <textarea name="fertilizer_history" class="form-control shadow-sm" rows="2"
                        placeholder="Describe fertilizers used...">{{ old('fertilizer_history') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-muted small">Pesticide History</label>
                    <textarea name="pesticide_history" class="form-control shadow-sm" rows="2"
                        placeholder="Describe pesticides used...">{{ old('pesticide_history') }}</textarea>
                </div>
            </div>

            {{-- STEP 2 ADDITION: Financial Transparency & Value Addition --}}
            <h4 class="text-primary mb-3 border-bottom pb-2 mt-2"><i class="bi bi-cash-stack"></i> Price Transparency &
                Value Addition</h4>
            <div
                class="row bg-primary bg-opacity-10 p-3 rounded-4 mb-4 mx-1 border border-primary border-opacity-25 shadow-sm">
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold text-dark">Farmer Price (Per Unit) <span
                            class="text-danger small">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-primary">৳</span>
                        <input type="number" step="0.01" name="farmer_price" class="form-control border-primary"
                            value="{{ old('farmer_price') }}" placeholder="Price paid to farmer" required>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold text-dark">Processing/Value Addition Cost <span
                            class="text-danger small">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-primary text-primary fw-bold">+</span>
                        <input type="number" step="0.01" name="processing_cost" class="form-control border-primary"
                            value="{{ old('processing_cost') }}" placeholder="Milling/Packaging cost" required>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold text-dark">Target Retail Price <span
                            class="text-danger small">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-dark text-white border-dark">৳</span>
                        <input type="number" step="0.01" name="target_retail_price" class="form-control border-dark"
                            value="{{ old('target_retail_price') }}" placeholder="Final consumer price" required>
                    </div>
                </div>
                <div class="col-12 mt-1">
                    <small class="text-primary fw-semibold"><i class="bi bi-shield-lock-fill"></i> This breakdown will be
                        visible to customers to build 100% trust.</small>
                </div>
            </div>

            {{-- Section 3: Quantity & Safety --}}
            <h4 class="text-success mb-3 border-bottom pb-2 mt-2"><i class="bi bi-box-seam"></i> Quantity & Quality
                Analysis</h4>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label text-success font-weight-bold">Total Quantity (KG/Unit) <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="total_quantity"
                        class="form-control border-success shadow-sm @error('total_quantity') is-invalid @enderror"
                        value="{{ old('total_quantity') }}" required>
                    @error('total_quantity')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-success font-weight-bold small">Moisture Level (%)</label>
                    <input type="text" name="moisture_level" class="form-control border-success shadow-sm"
                        value="{{ old('moisture_level', '12%') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-success font-weight-bold">Manufacturing Date <span
                            class="text-danger">*</span></label>
                    <input type="date" name="manufacturing_date" class="form-control border-success shadow-sm"
                        value="{{ old('manufacturing_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-success font-weight-bold small">Harvest Date</label>
                    <input type="date" name="harvest_date" class="form-control border-success shadow-sm"
                        value="{{ old('harvest_date') }}">
                </div>
            </div>

            {{-- Section 4: Advanced World-Class Analytics --}}
            <h4 class="text-warning mb-3 border-bottom pb-2 mt-2"><i class="bi bi-patch-check"></i> Certification &
                Storage</h4>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold text-warning small">Production Cost (Per Unit)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-warning text-white">৳</span>
                        <input type="number" step="0.01" name="production_cost_per_unit"
                            class="form-control border-warning shadow-sm" value="{{ old('production_cost_per_unit') }}"
                            placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold text-muted small">Certification Type</label>
                    <select name="certification_type" class="form-select border-warning shadow-sm">
                        <option value="Standard">Standard</option>
                        <option value="Organic">Organic (অর্গানিক)</option>
                        <option value="GAP">GAP Certified</option>
                        <option value="ISO">ISO Certified</option>
                        <option value="Non-GMO">Non-GMO</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold text-muted small">Storage Condition</label>
                    <input type="text" name="storage_condition" class="form-control border-warning shadow-sm"
                        value="{{ old('storage_condition') }}" placeholder="e.g. 18°C, Dry">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label font-weight-bold text-muted small">Target Market</label>
                    <input type="text" name="target_market" class="form-control border-warning shadow-sm"
                        value="{{ old('target_market') }}" placeholder="e.g. Export / Local Retail">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold text-secondary small">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control border-secondary shadow-sm"
                        value="{{ old('expiry_date') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold text-secondary small">Water Footprint (Approx.)</label>
                    <input type="text" name="water_footprint" class="form-control border-secondary shadow-sm"
                        placeholder="e.g. 500 Liters/KG">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="alert alert-info py-2 small w-100 mb-0 shadow-sm border-info" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle-fill text-primary"></i> <strong>Note:</strong> AI Safety Score will be
                        calculated during QC.
                    </div>
                </div>
            </div>

            <div class="mt-5 border-top pt-4 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                    <i class="bi bi-qr-code"></i> Create Verified Batch
                </button>
                <button type="reset" class="btn btn-outline-secondary btn-lg px-3 ms-2 rounded-pill">Reset Form</button>
            </div>
        </form>
    </div>

    <script>
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude.toFixed(6);
                    document.getElementById('lon').value = position.coords.longitude.toFixed(6);
                    document.getElementById('gps_msg').innerHTML =
                        "<i class='bi bi-check-circle-fill text-success'></i> Farm Location Authenticated: " +
                        position.coords.latitude.toFixed(4) + ", " + position.coords.longitude.toFixed(4);
                }, function(error) {
                    document.getElementById('gps_msg').innerHTML =
                        "<i class='bi bi-exclamation-triangle-fill text-danger'></i> GPS Error: Location required for high-trust batches.";
                });
            }
        };
    </script>

    <style>
        .rounded-4 {
            border-radius: 1.25rem !important;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 0.6rem 0.75rem;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }
    </style>
@endsection
