@extends("admin.layout.erp.app")
@section("content")
<x-alert/>

<div class="container-fluid py-4">
    {{-- ১. স্মার্ট অ্যানালিটিক্স সেকশন --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Active Batches</h6>
                            <h3 class="fw-bold mb-0">{{ $batches->total() }}</h3>
                        </div>
                        <i class="bi bi-diagram-3 fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Export Grade (A+)</h6>
                            <h3 class="fw-bold mb-0">{{ $batches->where('quality_grade', 'A+')->count() }}</h3>
                        </div>
                        <i class="bi bi-patch-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Processing Stage</h6>
                            <h3 class="fw-bold mb-0">{{ $batches->where('current_location', 'Processing Center')->count() }}</h3>
                        </div>
                        <i class="bi bi-truck fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('batches.trashed') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm bg-white text-danger border border-danger-subtle h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Trash Bin</h6>
                                <h4 class="fw-bold mb-0 text-danger">Recover Batches</h4>
                            </div>
                            <i class="bi bi-trash3 fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ২. হেডার এবং মাল্টি-ভেন্ডর ফিল্টারিং --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-qr-code-scan text-primary me-2"></i>Smart Supply Chain Intelligence
                    </h5>
                    <p class="text-muted small mb-0">Real-time tracking of product batches from Farmer to Market.</p>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end gap-2 mt-3 mt-md-0">
                    <form action="{{ URL::current() }}" method="GET" class="d-flex gap-1">
                        <input type="text" name="search" class="form-control form-control-sm shadow-none" placeholder="Search Batch/Product/Farmer..." value="{{ request('search') }}" style="width: 250px;">
                        <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-search"></i></button>
                    </form>
                    <a href="{{ route('batches.create') }}" class="btn btn-success btn-sm px-3 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> New Entry
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ৩. মেইন ডাটা টেবিল --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Batch Identity</th>
                            <th>Supply Chain Stage</th>
                            <th>Product & Analytics</th>
                            <th>Production Cost</th>
                            <th>QC & Compliance</th>
                            <th class="text-center">QR Identity</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                        {{-- ডাইনামিক লেফট বর্ডার স্ট্যাটাস অনুযায়ী --}}
                        @php
                            $borderColor = '#ffc107'; // Default Pending
                            if($batch->qc_status == 'approved') $borderColor = '#198754';
                            if($batch->qc_status == 'rejected') $borderColor = '#dc3545';
                        @endphp
                        <tr style="border-left: 5px solid {{ $borderColor }}">
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border mb-1">#{{ $batch->batch_no }}</span>
                                <div class="small text-muted"><i class="bi bi-calendar3"></i> {{ $batch->created_at->format('d M, Y') }}</div>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2 bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ $batch->farmer->name ?? 'Vendor' }}</div>
                                        <span class="badge bg-info-subtle text-info x-small" style="font-size: 10px;">
                                            Current: {{ $batch->current_location ?? 'Farmer Field' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold text-dark">{{ $batch->product->name ?? 'N/A' }}</div>
                                <div class="d-flex gap-1 mt-1">
                                    <small class="badge bg-primary-subtle text-primary border-primary-subtle">
                                        {{ $batch->total_quantity }} Units
                                    </small>
                                    <small class="badge bg-secondary-subtle text-secondary">
                                        M: {{ $batch->moisture_level ?? '0' }}%
                                    </small>
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold text-dark">৳ {{ number_format($batch->production_cost_per_unit, 2) }}</div>
                                <small class="text-muted">Per Unit Cost</small>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    {{-- ডাইনামিক স্ট্যাটাস ব্যাজ কালার --}}
                                    @php
                                        $statusClass = 'bg-warning text-dark';
                                        if($batch->qc_status == 'approved') $statusClass = 'bg-success';
                                        if($batch->qc_status == 'rejected') $statusClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $statusClass }} align-self-start mb-1">
                                        {{ strtoupper($batch->qc_status) }}
                                    </span>
                                    <small class="fw-bold text-muted">Grade: <span class="text-primary">{{ $batch->quality_grade ?? 'Pending' }}</span></small>

                                    @if($batch->qc_status == 'pending')
                                    <button class="btn btn-sm btn-link p-0 text-start text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#qcModal{{$batch->id}}">
                                        <i class="bi bi-patch-check"></i> Verify Now
                                    </button>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#qrModal{{$batch->id}}">
                                    {!! QrCode::size(45)->generate(route('public.trace', ['batch_no' => $batch->batch_no])) !!}
                                </a>
                                <div class="x-small text-muted mt-1" style="font-size: 10px;">Click to Print</div>
                            </td>

                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm border rounded bg-white">
                                    <a href="{{ route('public.trace', ['batch_no' => $batch->batch_no]) }}" target="_blank" class="btn btn-white btn-sm text-info border-end" title="Full Traceability">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('batches.edit', $batch->id) }}" class="btn btn-white btn-sm text-primary border-end" title="Edit Batch">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('batches.delete', $batch->id) }}" method="POST" onsubmit="return confirm('Archive this batch?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm text-danger" title="Move to Trash">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Quality Audit Modal --}}
                        <div class="modal fade" id="qcModal{{$batch->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content border-0 shadow-lg">
                                    <form action="{{ route('batches.approve', $batch->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-light">
                                            <h6 class="modal-title fw-bold">Quality Audit Analysis</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-success">Assign Quality Grade</label>
                                                <select name="quality_grade" class="form-select form-select-sm shadow-none" required>
                                                    <option value="A+">Grade A+ (Export Ready)</option>
                                                    <option value="A">Grade A (Premium)</option>
                                                    <option value="B">Grade B (Standard)</option>
                                                    <option value="C">Grade C (Local)</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Inspector Remarks</label>
                                                <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Describe quality..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-3">
                                            <button type="submit" class="btn btn-success btn-sm w-100 py-2 fw-bold">Approve & Release Batch</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- QR Label Print Modal (সঠিক লিঙ্কিং সহ) --}}
                        <div class="modal fade" id="qrModal{{$batch->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content text-center border-0 shadow-lg">
                                    <div class="modal-body p-4" id="printArea{{$batch->id}}">
                                        <h6 class="fw-bold mb-1">SMART TRACE LABEL</h6>
                                        <p class="x-small text-muted mb-3" style="font-size: 10px;">Blockchain Verified Supply Chain</p>
                                        <div class="d-inline-block border p-2 bg-white mb-3">
                                            {!! QrCode::size(150)->generate(route('public.trace', ['batch_no' => $batch->batch_no])) !!}
                                        </div>
                                        <div class="bg-light p-2 rounded small mb-3">
                                            <strong class="d-block">{{ $batch->batch_no }}</strong>
                                            <span class="text-primary fw-bold">{{ $batch->product->name ?? '' }}</span><br>
                                            <small class="text-muted">Farmer: {{ $batch->farmer->name ?? 'N/A' }}</small>
                                        </div>
                                        <button class="btn btn-primary btn-sm w-100 no-print" onclick="printDiv('printArea{{$batch->id}}')">
                                            <i class="bi bi-printer me-2"></i>Print Label
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        {{-- ৪. এম্পটি স্টেট ইলাস্ট্রেশন --}}
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-3 text-muted opacity-25">
                                    <i class="bi bi-search" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No supply chain data found!</h5>
                                <p class="text-muted small">Try searching with a different batch number, product, or farmer name.</p>
                                <a href="{{ route('batches.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-plus-circle"></i> Create First Batch
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ৫. প্যাজিনেশন --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $batches->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- কাস্টম স্টাইল --}}
<style>
    .table thead th { font-size: 11px; font-weight: 700; color: #495057; padding: 15px 10px; border-bottom: 2px solid #dee2e6; }
    .btn-white { background: #fff; border: none; }
    .btn-white:hover { background: #f8f9fa; color: #000; }
    tr:hover { background-color: rgba(13, 110, 253, 0.03) !important; transition: 0.3s; }
    .avatar-sm { display: inline-block; text-align: center; }
    .x-small { font-size: 11px; }

    @media print {
        .no-print { display: none !important; }
        body * { visibility: hidden; }
        #printAreaContent, #printAreaContent * { visibility: visible; }
        #printAreaContent { position: absolute; left: 0; top: 0; width: 100%; }
    }
</style>

<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = "<html><head><title>Print Label</title></head><body style='text-align:center; padding-top:50px;'>" + printContents + "</body></html>";
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
</script>

@endsection
