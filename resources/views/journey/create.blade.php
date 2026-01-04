@extends("layout.erp.app")
@section("content")

<h1 class="mb-3">Record Product Handover (New Journey)</h1>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{URL("journey/save")}}" method="POST" class="p-4 border rounded shadow-sm bg-light">
    @csrf

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">Select Product <span class="text-danger">*</span></label>
            <select name="product_id" class="form-select form-control" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">Seller (From) <span class="text-danger">*</span></label>
            <select name="seller_id" class="form-select form-control" required>
                <option value="">-- Select Seller --</option>
                @foreach($stakeholders as $stakeholder)
                    <option value="{{ $stakeholder->id }}">{{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold">Buyer (To) <span class="text-danger">*</span></label>
            <select name="buyer_id" class="form-select form-control" required>
                <option value="">-- Select Buyer --</option>
                @foreach($stakeholders as $stakeholder)
                    <option value="{{ $stakeholder->id }}">{{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label font-weight-bold text-primary">Buying Price (৳)</label>
            <input type="number" step="0.01" name="buying_price" id="buying_price" class="form-control calc-trigger" placeholder="0.00" required>
        </div>

        <div class="col-md-2 mb-3">
            <label class="form-label font-weight-bold text-info">Extra Cost (৳)</label>
            <input type="number" step="0.01" name="extra_cost" id="extra_cost" class="form-control calc-trigger" value="0">
        </div>

        <div class="col-md-2 mb-3">
            <label class="form-label font-weight-bold text-warning">Profit Margin (%)</label>
            <input type="number" step="0.1" name="profit_percent" id="profit_percent" class="form-control calc-trigger" placeholder="e.g. 10" required>
        </div>

        <div class="col-md-2 mb-3">
            <label class="form-label font-weight-bold text-warning">Profit (৳)</label>
            <input type="number" name="profit_margin" id="profit_margin" class="form-control bg-light" readonly placeholder="Calculated">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label font-weight-bold text-success">Selling Price (Final)</label>
            <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control bg-white fw-bold" readonly>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label font-weight-bold">Current Stage <span class="text-danger">*</span></label>
            <select name="current_stage" class="form-select form-control" required>
                <option value="">-- Select Current Stage --</option>
                <option value="Farmer">Farmer</option>
                <option value="Miller">Miller</option>
                <option value="Wholesaler">Wholesaler</option>
                <option value="Retailer">Retailer</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Save Handover</button>
        <a href="{{URL('journey')}}" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<script>
    document.querySelectorAll('.calc-trigger').forEach(input => {
        input.addEventListener('input', function() {
            let buy = parseFloat(document.getElementById('buying_price').value) || 0;
            let cost = parseFloat(document.getElementById('extra_cost').value) || 0;
            let percent = parseFloat(document.getElementById('profit_percent').value) || 0;

            let baseTotal = buy + cost;
            let profitAmount = (baseTotal * percent) / 100; // পার্সেন্টেজ থেকে টাকা বের করা
            let finalPrice = baseTotal + profitAmount;

            document.getElementById('profit_margin').value = profitAmount.toFixed(2);
            document.getElementById('selling_price').value = finalPrice.toFixed(2);
        });
    });
</script>

@endsection
