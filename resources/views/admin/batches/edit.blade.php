@extends('admin.layout.erp.app')
@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="text-primary fw-bold mb-0">
                            <i class="bi bi-pencil-square"></i> Update Batch Lifecycle
                        </h3>
                        <p class="text-muted small">Modifying Traceability Data for Batch:
                            <span class="badge bg-primary">{{ $batch->batch_no }}</span></p>
                    </div>
                    <a href="{{ URL('admin/batches') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                        <i class="bi bi-arrow-left"></i> Back to Batch List
                    </a>
                </div>

                {{-- Form Start --}}
                <form action="{{ route('batches.update', $batch->id) }}" method="POST"
                    class="p-4 border-0 shadow-lg rounded-4 bg-white">
                    @csrf
                    @method('POST')

                    {{-- Section 1: Identification & GPS --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-dark fw-bold border-start border-primary border-4 ps-2 mb-3">Batch Identity & Origin</h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Select Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-select shadow-none border-primary-subtle" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $batch->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Source Farmer/Vendor <span class="text-danger">*</span></label>
                            <select name="initial_farmer_id" class="form-select shadow-none border-primary-subtle" required>
                                @foreach ($farmers as $farmer)
                                    <option value="{{ $farmer->id }}"
                                        {{ $batch->initial_farmer_id == $farmer->id ? 'selected' : '' }}>
                                        {{ $farmer->name }} ({{ $farmer->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Supply Chain Stage</label>
                            <input type="text" name="current_location" value="{{ $batch->current_location }}"
                                class="form-control shadow-none" placeholder="e.g. Processing Center">
                        </div>
                    </div>

                    {{-- GPS Coordinates --}}
                    <div class="row bg-light p-3 rounded-3 mb-4 mx-0 border border-info-subtle">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-info small"><i class="bi bi-geo-alt-fill"></i> Field Latitude (Locked)</label>
                            <input type="text" name="latitude" value="{{ $batch->latitude }}"
                                class="form-control border-0 bg-white shadow-sm" readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold text-info small"><i class="bi bi-geo-alt-fill"></i> Field Longitude (Locked)</label>
                            <input type="text" name="longitude" value="{{ $batch->longitude }}"
                                class="form-control border-0 bg-white shadow-sm" readonly>
                        </div>
                    </div>

                    {{-- STEP 2 ADDITION: Financial Transparency & Value Addition (High-Visibility Box) --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary fw-bold border-start border-primary border-4 ps-2 mb-3">Price Transparency & Value Addition</h5>
                        </div>
                        <div class="col-12">
                            <div class="row bg-primary bg-opacity-10 p-4 rounded-4 mx-0 border border-primary border-opacity-25">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold text-dark">Farmer Price (Per Unit)</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-primary">৳</span>
                                        <input type="number" step="0.01" name="farmer_price"
                                            value="{{ $batch->farmer_price }}" class="form-control border-primary">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold text-dark">Processing/Value Addition Cost</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-primary text-primary fw-bold">+</span>
                                        <input type="number" step="0.01" name="processing_cost"
                                            value="{{ $batch->processing_cost }}" class="form-control border-primary">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold text-dark">Target Retail Price</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-dark text-white border-dark">৳</span>
                                        <input type="number" step="0.01" name="target_retail_price"
                                            value="{{ $batch->target_retail_price }}" class="form-control border-dark">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <small class="text-primary fw-semibold"><i class="bi bi-info-circle"></i> Updating these prices will automatically update the Public Traceability page.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Cultivation & Seed Info --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-dark fw-bold border-start border-info border-4 ps-2 mb-3">Seed & Cultivation Intelligence</h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Seed Brand</label>
                            <input type="text" name="seed_brand" value="{{ $batch->seed_brand }}"
                                class="form-control shadow-none">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Seed Variety</label>
                            <input type="text" name="seed_variety" value="{{ $batch->seed_variety }}"
                                class="form-control shadow-none">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold text-danger">Sowing Date</label>
                            <input type="date" name="sowing_date" value="{{ $batch->sowing_date }}"
                                class="form-control shadow-none border-danger-subtle">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold text-danger">Last Pesticide Applied</label>
                            <input type="date" name="last_pesticide_date" value="{{ $batch->last_pesticide_date }}"
                                class="form-control shadow-none border-danger-subtle">
                        </div>
                    </div>

                    {{-- Section 3: Quantity & Quality Analysis --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-dark fw-bold border-start border-success border-4 ps-2 mb-3">Stock & Quality Metrics</h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-success">Total Quantity (Units)</label>
                            <input type="number" step="0.01" name="total_quantity" value="{{ $batch->total_quantity }}"
                                class="form-control shadow-none border-success-subtle" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-success small">Moisture Level (%)</label>
                            <input type="text" name="moisture_level" value="{{ $batch->moisture_level }}"
                                class="form-control shadow-none border-success-subtle">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-success">Manufacturing Date</label>
                            <input type="date" name="manufacturing_date" value="{{ $batch->manufacturing_date }}"
                                class="form-control shadow-none border-success-subtle" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-success">Harvest Date</label>
                            <input type="date" name="harvest_date" value="{{ $batch->harvest_date }}"
                                class="form-control shadow-none border-success-subtle">
                        </div>
                    </div>

                    {{-- Section 4: QC & Final Status --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-dark fw-bold border-start border-warning border-4 ps-2 mb-3">Compliance & QC Audit</h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-secondary">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ $batch->expiry_date }}"
                                class="form-control shadow-none">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-primary">Quality Grade</label>
                            <select name="quality_grade" class="form-select shadow-none">
                                <option value="A+" {{ $batch->quality_grade == 'A+' ? 'selected' : '' }}>Grade A+ (Export Quality)</option>
                                <option value="A" {{ $batch->quality_grade == 'A' ? 'selected' : '' }}>Grade A (Premium)</option>
                                <option value="B" {{ $batch->quality_grade == 'B' ? 'selected' : '' }}>Grade B (Standard)</option>
                                <option value="C" {{ $batch->quality_grade == 'C' ? 'selected' : '' }}>Grade C (Local)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Live QC Status</label>
                            <div class="mt-1">
                                @php
                                    $statusClass = [
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'pending' => 'bg-warning text-dark',
                                    ][$batch->qc_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge py-2 px-3 {{ $statusClass }}">
                                    <i class="bi {{ $batch->qc_status == 'approved' ? 'bi-check-circle' : 'bi-shield-exclamation' }}"></i>
                                    {{ strtoupper($batch->qc_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">QC Specialist Remarks</label>
                            <textarea name="qc_remarks" class="form-control shadow-none border-warning-subtle" rows="2"
                                placeholder="Internal notes about quality audit...">{{ $batch->qc_remarks }}</textarea>
                        </div>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="mt-4 border-top pt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                            <i class="bi bi-arrow-repeat"></i> Update Lifecycle Data
                        </button>
                        <a href="{{ URL('admin/batches') }}"
                            class="btn btn-link text-secondary text-decoration-none ms-3">Cancel Changes</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.05);
        }
        .form-label { font-size: 0.9rem; color: #495057; }
        .rounded-4 { border-radius: 1.25rem !important; }
        .input-group-text { border-radius: 10px 0 0 10px; }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
    </style>
@endsection
