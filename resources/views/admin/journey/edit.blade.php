@extends("admin.layout.erp.app")
@section("content")

<div class="d-flex justify-content-between align-items-center mb-3 mt-2">
    <h4 class="text-primary fw-bold"><i class="ri-edit-box-line me-2"></i>Edit Handover Record</h4>
    <a href="{{URL('admin/journey')}}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="ri-arrow-left-line"></i> Back to History
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger py-2 mb-3">
    <ul class="mb-0 small">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{URL("admin/journey/update/".$journey->id)}}" method="POST" class="p-4 border rounded shadow-sm bg-white">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label fw-bold small text-muted">Tracking No</label>
            <input type="text" class="form-control bg-light fw-bold text-primary" value="{{ $journey->tracking_no }}" readonly style="border-style: dashed;">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold small">Select Batch <span class="text-danger">*</span></label>
            <select name="batch_id" class="form-select border-primary" required>
                <option value="">-- Choose Batch --</option>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}" {{ $journey->batch_id == $batch->id ? 'selected' : '' }}>
                        {{ $batch->batch_code ?? 'BC-'.$batch->id }} {{ $batch->batch_name ? "($batch->batch_name)" : "" }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold small">Product <span class="text-danger">*</span></label>
            <select name="product_id" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $journey->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold small">Stage <span class="text-danger">*</span></label>
            <select name="current_stage" class="form-select" required>
                <option value="Farmer" {{ $journey->current_stage == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                <option value="Miller" {{ $journey->current_stage == 'Miller' ? 'selected' : '' }}>Miller</option>
                <option value="Wholesaler" {{ $journey->current_stage == 'Wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                <option value="Retailer" {{ $journey->current_stage == 'Retailer' ? 'selected' : '' }}>Retailer</option>
            </select>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light shadow-sm">
                <label class="form-label fw-bold small text-danger mb-2"><i class="ri-user-unfollow-line"></i> Seller (From) *</label>
                <select name="seller_id" class="form-select" required>
                    @foreach($stakeholders as $stakeholder)
                        <option value="{{ $stakeholder->id }}" {{ $journey->seller_id == $stakeholder->id ? 'selected' : '' }}>
                            {{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light shadow-sm">
                <label class="form-label fw-bold small text-success mb-2"><i class="ri-user-received-line"></i> Buyer (To) *</label>
                <select name="buyer_id" class="form-select" required>
                    @foreach($stakeholders as $stakeholder)
                        <option value="{{ $stakeholder->id }}" {{ $journey->buyer_id == $stakeholder->id ? 'selected' : '' }}>
                            {{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <hr class="my-4 border-2 opacity-50">

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label fw-bold small text-primary">Buying Price (৳)</label>
            <input type="number" step="0.01" name="buying_price" id="buying_price" class="form-control calc-trigger" value="{{ $journey->buying_price }}" required>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold small text-info">Extra Cost (৳)</label>
            <input type="number" step="0.01" name="extra_cost" id="extra_cost" class="form-control calc-trigger" value="{{ $journey->extra_cost }}">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold small text-warning">Profit (%)</label>
            @php
                $base = $journey->buying_price + $journey->extra_cost;
                $old_percent = $base > 0 ? ($journey->profit_margin / $base) * 100 : 0;
            @endphp
            <input type="number" step="0.1" name="profit_percent" id="profit_percent" class="form-control calc-trigger" value="{{ round($old_percent, 2) }}" required>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold small text-muted">Profit (৳)</label>
            <input type="number" id="profit_margin_val" class="form-control bg-light border-0 shadow-none" readonly value="{{ $journey->profit_margin }}">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold small text-success">Selling Price (Final)</label>
            <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control bg-white fw-bold text-success border-success shadow-sm" style="font-size: 1.1rem;" readonly value="{{ $journey->selling_price }}">
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label fw-bold small">Location</label>
            <input type="text" name="location" class="form-control" value="{{ $journey->location }}" placeholder="e.g. Dhaka">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold small">Quality Status</label>
            <select name="quality_status" class="form-select">
                <option value="Good" {{ $journey->quality_status == 'Good' ? 'selected' : '' }}>Good</option>
                <option value="Premium" {{ $journey->quality_status == 'Premium' ? 'selected' : '' }}>Premium</option>
                <option value="Standard" {{ $journey->quality_status == 'Standard' ? 'selected' : '' }}>Standard</option>
                <option value="Damaged" {{ $journey->quality_status == 'Damaged' ? 'selected' : '' }}>Damaged</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold small">Remarks</label>
            <textarea name="remarks" class="form-control" rows="1" placeholder="Optional notes...">{{ $journey->remarks }}</textarea>
        </div>
    </div>

    <div class="pt-3 border-top text-end">
        <button type="submit" class="btn btn-primary px-5 shadow fw-bold">
            <i class="ri-refresh-line me-1"></i> Update Record
        </button>
        <a href="{{ URL('admin/journey') }}" class="btn btn-light px-4 border ms-2">Cancel</a>
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

    document.querySelectorAll('.calc-trigger').forEach(input => {
        input.addEventListener('input', updateCalculation);
    });

    window.onload = updateCalculation;
</script>

@endsection
