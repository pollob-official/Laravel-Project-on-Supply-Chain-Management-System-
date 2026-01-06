@extends("layout.erp.app")
@section("content")

<div class="d-flex justify-content-between align-items-center mb-2 mt-2">
    <h3><i class="bi bi-pencil-square me-2"></i>Edit Product Journey</h3>
    <a href="{{URL('journey')}}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Handover History
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{URL("journey/update/".$journey->id)}}" method="POST" class="p-4 border rounded shadow-sm bg-light">
    @csrf
    @method('POST') {{-- আপনার কন্ট্রোলারে যদি আলাদা মেথড থাকে তবে এখানে PUT দিতে পারেন --}}

    <div class="row">
        <div class="col-md-3 mb-2">
            <label class="form-label font-weight-bold text-muted">Tracking No</label>
            <input type="text" class="form-control bg-white" value="{{ $journey->tracking_no }}" readonly>
        </div>

        <div class="col-md-3 mb-2">
            <label class="form-label font-weight-bold">Product <span class="text-danger">*</span></label>
            <select name="product_id" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $journey->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <label class="form-label font-weight-bold">Seller (From) <span class="text-danger">*</span></label>
            <select name="seller_id" class="form-select" required>
                @foreach($stakeholders as $stakeholder)
                    <option value="{{ $stakeholder->id }}" {{ $journey->seller_id == $stakeholder->id ? 'selected' : '' }}>
                        {{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <label class="form-label font-weight-bold">Buyer (To) <span class="text-danger">*</span></label>
            <select name="buyer_id" class="form-select" required>
                @foreach($stakeholders as $stakeholder)
                    <option value="{{ $stakeholder->id }}" {{ $journey->buyer_id == $stakeholder->id ? 'selected' : '' }}>
                        {{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <hr class="my-2">

    <div class="row">
        <div class="col-md-3 mb-2">
            <label class="form-label font-weight-bold text-primary">Buying Price (৳)</label>
            <input type="number" step="0.01" name="buying_price" id="buying_price" class="form-control calc-trigger" value="{{ $journey->buying_price }}" required>
        </div>

        <div class="col-md-2 mb-2">
            <label class="form-label font-weight-bold text-info">Extra Cost (৳)</label>
            <input type="number" step="0.01" name="extra_cost" id="extra_cost" class="form-control calc-trigger" value="{{ $journey->extra_cost }}">
        </div>

        <div class="col-md-2 mb-2">
            <label class="form-label font-weight-bold text-warning">Profit Margin (%)</label>
            @php
                $base = $journey->buying_price + $journey->extra_cost;
                $old_percent = $base > 0 ? ($journey->profit_margin / $base) * 100 : 0;
            @endphp
            <input type="number" step="0.1" name="profit_percent" id="profit_percent" class="form-control calc-trigger" value="{{ round($old_percent, 2) }}" required>
        </div>

        <div class="col-md-2 mb-2">
            <label class="form-label font-weight-bold text-warning">Profit (৳)</label>
            <input type="number" id="profit_margin_val" class="form-control bg-light" readonly value="{{ $journey->profit_margin }}">
        </div>

        <div class="col-md-3 mb-2">
            <label class="form-label font-weight-bold text-success">Selling Price (Final)</label>
            <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control bg-white fw-bold" readonly value="{{ $journey->selling_price }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <label class="form-label font-weight-bold">Current Stage <span class="text-danger">*</span></label>
            <select name="current_stage" class="form-select" required>
                <option value="Farmer" {{ $journey->current_stage == 'Farmer' ? 'selected' : '' }}>Farmer (কৃষক পর্যায়)</option>
                <option value="Miller" {{ $journey->current_stage == 'Miller' ? 'selected' : '' }}>Miller (মিলিং পর্যায়)</option>
                <option value="Wholesaler" {{ $journey->current_stage == 'Wholesaler' ? 'selected' : '' }}>Wholesaler (পাইকারি পর্যায়)</option>
                <option value="Retailer" {{ $journey->current_stage == 'Retailer' ? 'selected' : '' }}>Retailer (খুচরা বিক্রয়)</option>
            </select>
        </div>
    </div>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save"></i> Update Journey Record
        </button>
    </div>
</form>

<script>
    function updateCalculation() {
        let buy = parseFloat(document.getElementById('buying_price').value) || 0;
        let cost = parseFloat(document.getElementById('extra_cost').value) || 0;
        let percent = parseFloat(document.getElementById('profit_percent').value) || 0;

        let baseTotal = buy + cost;
        let profitAmount = (baseTotal * percent) / 100;
        let finalPrice = baseTotal + profitAmount;

        document.getElementById('profit_margin_val').value = profitAmount.toFixed(2);
        document.getElementById('selling_price').value = finalPrice.toFixed(2);
    }

    // ইনপুট পরিবর্তনের সাথে সাথে ক্যালকুলেশন কল হবে
    document.querySelectorAll('.calc-trigger').forEach(input => {
        input.addEventListener('input', updateCalculation);
    });

    // পেজ লোড হওয়ার সময় একবার রান করবে
    window.onload = updateCalculation;
</script>

@endsection
