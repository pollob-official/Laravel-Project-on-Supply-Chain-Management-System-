@extends('admin.layout.erp.app')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h4 class="text-primary fw-bold"><i class="ri-route-line me-2"></i>Record Product Handover</h4>
        <a href="{{ URL('admin/journey') }}" class="btn btn-secondary btn-sm shadow-sm">
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

    <form action="{{ URL('admin/journey/save') }}" method="POST" class="p-4 border rounded shadow-sm bg-white">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold small">Select Batch <span class="text-danger">*</span></label>
                <select name="batch_id" class="form-select border-primary" required>
                    <option value="">-- Choose Batch --</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}">
                            {{ $batch->batch_code ?? 'BC-'.$batch->id }} {{ $batch->batch_name ? "($batch->batch_name)" : "" }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small">Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small">Current Stage <span class="text-danger">*</span></label>
                <select name="current_stage" class="form-select" required>
                    <option value="">-- Select Stage --</option>
                    <option value="Farmer">Farmer (Origin)</option>
                    <option value="Miller">Miller (Processor)</option>
                    <option value="Wholesaler">Wholesaler</option>
                    <option value="Retailer">Retailer (Final)</option>
                </select>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <label class="form-label fw-bold small text-danger mb-2"><i class="ri-user-unfollow-line"></i> Seller (From) *</label>
                    <select name="seller_id" class="form-select" required>
                        <option value="">-- Select Seller --</option>
                        @foreach ($stakeholders as $stakeholder)
                            <option value="{{ $stakeholder->id }}">{{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <label class="form-label fw-bold small text-success mb-2"><i class="ri-user-received-line"></i> Buyer (To) *</label>
                    <select name="buyer_id" class="form-select" required>
                        <option value="">-- Select Buyer --</option>
                        @foreach ($stakeholders as $stakeholder)
                            <option value="{{ $stakeholder->id }}">{{ $stakeholder->name }} ({{ ucfirst($stakeholder->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <hr class="my-4 border-2 opacity-50">

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label fw-bold small text-primary">Buying Price (৳)</label>
                <input type="number" step="0.01" name="buying_price" id="buying_price" class="form-control calc-trigger" placeholder="0.00" required>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold small text-info">Extra Cost (৳)</label>
                <input type="number" step="0.01" name="extra_cost" id="extra_cost" class="form-control calc-trigger" value="0">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold small text-warning">Profit (%)</label>
                <input type="number" step="0.1" name="profit_percent" id="profit_percent" class="form-control calc-trigger" placeholder="e.g. 10" required>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold small text-muted">Profit (৳)</label>
                <input type="number" id="profit_margin" class="form-control bg-light border-0 shadow-none" readonly placeholder="0.00">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold small text-success">Selling Price (Final)</label>
                <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control bg-white fw-bold text-success border-success shadow-sm" style="font-size: 1.1rem;" readonly>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold small">Location</label>
                <input type="text" name="location" class="form-control" placeholder="e.g. Bogura">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small">Quality Status</label>
                <select name="quality_status" class="form-select">
                    <option value="Good">Good</option>
                    <option value="Premium">Premium</option>
                    <option value="Standard">Standard</option>
                    <option value="Damaged">Damaged</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold small">Remarks</label>
                <textarea name="remarks" class="form-control" rows="1" placeholder="Optional notes..."></textarea>
            </div>
        </div>

        <div class="pt-3 border-top text-end">
            <button type="submit" class="btn btn-primary px-5 shadow fw-bold"><i class="ri-save-line me-1"></i> Save Journey</button>
            <a href="{{ URL('admin/journey') }}" class="btn btn-light px-4 border ms-2">Cancel</a>
        </div>
    </form>

    <script>
        document.querySelectorAll('.calc-trigger').forEach(input => {
            input.addEventListener('input', function() {
                let buy = parseFloat(document.getElementById('buying_price').value) || 0;
                let cost = parseFloat(document.getElementById('extra_cost').value) || 0;
                let percent = parseFloat(document.getElementById('profit_percent').value) || 0;

                let baseTotal = buy + cost;
                let profitAmount = (baseTotal * percent) / 100;
                let finalPrice = baseTotal + profitAmount;

                document.getElementById('profit_margin').value = profitAmount.toFixed(2);
                document.getElementById('selling_price').value = finalPrice.toFixed(2);
            });
        });
    </script>
@endsection
