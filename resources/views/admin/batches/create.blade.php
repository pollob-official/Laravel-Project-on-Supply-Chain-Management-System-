@extends('admin.layout.erp.app')
@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary fw-bold"><i class="bi bi-qr-code-scan"></i> Dynamic Batch & Traceability Initiation</h3>
            {{-- সংশোধিত রাউট নাম: index --}}
            <a href="{{ route('batches.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3">Back</a>
        </div>

        <form action="{{ route('batches.store') }}" method="POST" class="p-4 border-0 rounded-4 shadow bg-white">
            @csrf

            {{-- Section 1: Source & Identification --}}
            <h4 class="text-muted mb-3 border-bottom pb-2">Step 1: Stakeholder & Product Selection</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Select Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-select shadow-sm" required onchange="updateDynamicUnits()">
                        <option value="">-- Choose Product --</option>
                        @foreach ($products as $product)
                            {{-- এখানে data-unit এ রিলেশনশিপ ডাটা পাস হচ্ছে --}}
                            <option value="{{ $product->id }}"
                                    data-unit="{{ $product->unit->name ?? 'Unit' }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Source Stakeholder Role <span class="text-danger">*</span></label>
                    <select id="role_selector" name="source_type" class="form-select shadow-sm" required onchange="filterStakeholders()">
                        <option value="">-- Select Role --</option>
                        <option value="farmer">Farmer (Primary Producer)</option>
                        <option value="miller">Miller / Supplier</option>
                        <option value="wholesaler">Wholesaler</option>
                        <option value="retailer">Retailer</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Select Specific Stakeholder <span class="text-danger">*</span></label>
                    <select name="source_id" id="source_id" class="form-select shadow-sm" required>
                        <option value="">-- Choose Name --</option>
                    </select>
                </div>
            </div>

            {{-- Section 2: Location (GPS + Manual) --}}
            <h4 class="text-muted mb-3 border-bottom pb-2 mt-2">Step 2: Traceability Location</h4>
            <div class="row bg-light p-3 rounded-4 mb-4 mx-1 border border-primary border-opacity-25">
                <div class="col-md-4 mb-2">
                    <label class="form-label fw-bold text-primary small">GPS Coordinates (Auto)</label>
                    <div class="input-group">
                        <input type="text" name="latitude" id="lat" class="form-control bg-white" readonly placeholder="Lat">
                        <input type="text" name="longitude" id="lon" class="form-control bg-white" readonly placeholder="Lon">
                    </div>
                </div>
                <div class="col-md-8 mb-2">
                    <label class="form-label fw-bold text-primary small">Manual Address (Village, Upazila, District) <span class="text-danger">*</span></label>
                    <input type="text" name="manual_location" class="form-control border-primary shadow-sm"
                           placeholder="Type detailed location for transparency" required>
                </div>
                <div class="col-12">
                    <small id="gps_status" class="text-muted"><i class="bi bi-broadcast"></i> Waiting for GPS signal...</small>
                </div>
            </div>

            {{-- Section 3: Farmer Specific Info --}}
            <div id="farmer_only_section" style="display: none;" class="animate__animated animate__fadeIn">
                <h4 class="text-info mb-3 border-bottom pb-2 mt-2"><i class="bi bi-seedling"></i> Seed & Cultivation Details</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="small fw-bold">Seed Brand/Variety</label>
                        <input type="text" name="seed_variety" class="form-control border-info shadow-sm" placeholder="e.g. BRRI 28">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small fw-bold">Sowing Date</label>
                        <input type="date" name="sowing_date" class="form-control border-info shadow-sm">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small fw-bold">Last Pesticide Date</label>
                        <input type="date" name="last_pesticide_date" class="form-control border-info shadow-sm">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small fw-bold">Harvest Date</label>
                        <input type="date" name="harvest_date" class="form-control border-info shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Section 4: Financial Transparency --}}
            <h4 class="text-primary mb-3 border-bottom pb-2 mt-2"><i class="bi bi-cash-stack"></i> Financial Transparency & Quantity</h4>
            <div class="row p-3 rounded-4 mb-4 mx-1 border shadow-sm" style="background-color: #f0f7ff; border-left: 5px solid #0d6efd !important;">

                <div class="col-12 mb-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                        Current Unit: <span id="current_unit_display" class="fw-bold text-warning">Not Selected</span>
                    </span>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="fw-bold">Buying Price (Per <span class="u_name">Unit</span>) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-primary">৳</span>
                        <input type="number" step="0.01" id="per_unit_price" name="buying_price_per_unit"
                               class="form-control border-primary" required oninput="calculateTotalBatchCost()">
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="fw-bold text-success">Total Quantity (<span class="u_name">Unit</span>) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.1" id="total_qty" name="total_quantity"
                               class="form-control border-success shadow-sm" required oninput="calculateTotalBatchCost()">
                        <span class="input-group-text bg-light u_name">Unit</span>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="fw-bold text-dark">Processing/Added Cost</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-warning">+</span>
                        <input type="number" step="0.01" id="processing_cost" name="processing_cost"
                               class="form-control border-warning shadow-sm" placeholder="Milling/Labor" oninput="calculateTotalBatchCost()">
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="fw-bold text-primary">Total Batch Investment</label>
                    <input type="text" id="total_batch_cost" class="form-control bg-dark text-white fw-bold border-0" readonly placeholder="0.00 ৳">
                    <small id="effective_price_box" class="text-danger fw-bold d-block mt-1"></small>
                </div>
            </div>

            {{-- Section 5: Other Info --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold">Manufacturing/Batch Date</label>
                    <input type="date" name="manufacturing_date" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold">Moisture Level (%)</label>
                    <input type="text" name="moisture_level" class="form-control shadow-sm" placeholder="e.g. 12%">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold">Certification (Optional)</label>
                    <select name="certification_type" class="form-select shadow-sm">
                        <option value="Standard">Standard</option>
                        <option value="GAP">GAP Certified</option>
                        <option value="Organic">Organic</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                    <i class="bi bi-qr-code"></i> Create World-Class Batch
                </button>
            </div>
        </form>
    </div>

    <script>
        // কন্ট্রোলার থেকে আসা স্টেকহোল্ডার ডাটা
        const stakeholders = @json($stakeholders);

        // ১. ইউনিট আপডেট লজিক
        function updateDynamicUnits() {
            const productSelect = document.getElementById('product_id');
            const selectedOption = productSelect.options[productSelect.selectedIndex];

            // ডাটা এট্রিবিউট থেকে ইউনিট নাম সংগ্রহ
            const unitName = selectedOption.getAttribute('data-unit') || 'Unit';

            // সব .u_name ক্লাস যুক্ত এলিমেন্টে টেক্সট বসানো
            document.querySelectorAll('.u_name').forEach(el => {
                el.innerText = unitName;
            });

            document.getElementById('current_unit_display').innerText = unitName;

            calculateTotalBatchCost();
        }

        // ২. রোল অনুযায়ী স্টেকহোল্ডার ফিল্টার
        function filterStakeholders() {
            const selectedRole = document.getElementById('role_selector').value;
            const sourceSelect = document.getElementById('source_id');
            const farmerSection = document.getElementById('farmer_only_section');

            sourceSelect.innerHTML = '<option value="">-- Choose Name --</option>';

            const filtered = stakeholders.filter(s => s.role === selectedRole);
            filtered.forEach(s => {
                const option = document.createElement('option');
                option.value = s.id;
                option.text = `${s.name} (${s.phone})`;
                sourceSelect.add(option);
            });

            // কৃষক হলে অতিরিক্ত সেকশন দেখানো
            farmerSection.style.display = (selectedRole === 'farmer') ? 'block' : 'none';
        }

        // ৩. ক্যালকুলেশন লজিক
        function calculateTotalBatchCost() {
            const unitPrice = parseFloat(document.getElementById('per_unit_price').value) || 0;
            const quantity = parseFloat(document.getElementById('total_qty').value) || 0;
            const processing = parseFloat(document.getElementById('processing_cost').value) || 0;

            const total = (unitPrice * quantity) + processing;

            // কারেন্সি ফরম্যাটিং
            document.getElementById('total_batch_cost').value = total.toLocaleString('bn-BD') + ' ৳';

            const effectiveBox = document.getElementById('effective_price_box');
            if(quantity > 0) {
                const realCost = (total / quantity).toFixed(2);
                const unit = document.getElementById('current_unit_display').innerText;
                effectiveBox.innerHTML = `<i class="bi bi-info-circle"></i> Real Cost: ${realCost} ৳ / ${unit}`;
            } else {
                effectiveBox.innerText = '';
            }
        }

        // ৪. অটো জিপিএস ডিটেকশন
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude.toFixed(6);
                    document.getElementById('lon').value = position.coords.longitude.toFixed(6);
                    document.getElementById('gps_status').innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> GPS Signal Locked.';
                }, function(error) {
                    document.getElementById('gps_status').innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger"></i> GPS Error: Using Manual Input.';
                });
            }
        };
    </script>

    <style>
        .rounded-4 { border-radius: 1.25rem !important; }
        .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); }
        .input-group-text { min-width: 45px; justify-content: center; }
        .u_name { font-weight: bold; color: #dc3545; }
    </style>
@endsection
