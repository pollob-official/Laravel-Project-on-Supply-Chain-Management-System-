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
                            <h6 class="text-white-50">Total Batches</h6>
                            <h3 class="fw-bold mb-0">{{ $batches->total() }}</h3>
                        </div>
                        <i class="bi bi-box-seam fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">QC Approved</h6>
                            <h3 class="fw-bold mb-0">{{ $batches->where('qc_status', 'approved')->count() }}</h3>
                        </div>
                        <i class="bi bi-patch-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50">Pending Audit</h6>
                            <h3 class="fw-bold mb-0">{{ $batches->where('qc_status', 'pending')->count() }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('batches.trashed') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm bg-white text-danger border border-danger-subtle">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Trash Bin</h6>
                                <h3 class="fw-bold mb-0">View All</h3>
                            </div>
                            <i class="bi bi-trash3 fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ২. হেডার এবং অ্যাকশন বাটন --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark"><i class="bi bi-list-stars text-primary"></i> Active Production Batches</h4>
        <div class="d-flex gap-2">
            <form action="{{ URL::current() }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search Batch No..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
            </form>
            <a href="{{ route('batches.create') }}" class="btn btn-success btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-circle"></i> Create New Batch
            </a>
        </div>
    </div>

    {{-- ৩. মেইন ডাটা টেবিল --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Batch Details</th>
                            <th>Product Info</th>
                            <th>Farmer & Origin</th>
                            <th>Status & Grade</th>
                            <th>QR Trace</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $batch->batch_no }}</div>
                                <small class="text-muted"><i class="bi bi-calendar3"></i> {{ $batch->created_at->format('d M, Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="ms-1">
                                        <div class="fw-bold text-primary">{{ $batch->product->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $batch->total_quantity }} Units</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-person-circle"></i> {{ $batch->farmer->name ?? 'Unknown' }}<br>
                                    <i class="bi bi-geo-alt text-danger"></i> {{ $batch->current_location }}
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $batch->qc_status == 'approved' ? 'bg-success-subtle text-success border-success' : 'bg-warning-subtle text-warning border-warning' }} border px-2">
                                    {{ strtoupper($batch->qc_status) }}
                                </span>
                                <div class="mt-1 small fw-bold">Grade: <span class="text-primary">{{ $batch->quality_grade ?? 'N/A' }}</span></div>
                            </td>
                            <td>
                                {{-- কিউআর ইমেজে ক্লিক করলে এখন মোডাল খুলবে --}}
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#qrModal{{$batch->id}}">
                                    <img src="{{ asset($batch->qr_code) }}" alt="QR" width="45" class="img-thumbnail shadow-sm p-1">
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm shadow-sm border" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item" href="{{ route('public.trace', $batch->batch_no) }}" target="_blank"><i class="bi bi-eye text-info"></i> Public View</a></li>
                                        <li><a class="dropdown-item" href="{{ route('batches.edit', $batch->id) }}"><i class="bi bi-pencil-square text-primary"></i> Edit Details</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('batches.delete', $batch->id) }}" method="POST" onsubmit="return confirm('Move to Trash?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash"></i> Move to Trash</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        {{-- ৪. কিউআর প্রিন্ট মোডাল (প্রতিটি রো এর জন্য আলাদা) --}}
                        <div class="modal fade" id="qrModal{{$batch->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light border-0">
                                        <h6 class="modal-title fw-bold">Label: {{ $batch->batch_no }}</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <img src="{{ asset($batch->qr_code) }}" class="img-fluid rounded border mb-3 p-2" style="max-width: 200px;">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-primary" onclick="window.print()">
                                                <i class="bi bi-printer"></i> Print Batch Label
                                            </button>
                                            <a href="{{ asset($batch->qr_code) }}" download class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-download"></i> Save Image
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3"><br>
                                <p class="text-muted">No active batches found in the ecosystem.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ৫. প্যাজিনেশন --}}
    <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Showing {{ $batches->firstItem() }} to {{ $batches->lastItem() }} of {{ $batches->total() }} batches</div>
        {{ $batches->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- প্রিন্ট করার সময় শুধু কিউআর কোড দেখানোর জন্য ছোট সিএসএস --}}
<style>
    @media print {
        body * { visibility: hidden; }
        .modal-body, .modal-body * { visibility: visible; }
        .modal-body { position: absolute; left: 0; top: 0; width: 100%; text-align: center; }
        .btn, .btn-outline-secondary { display: none !important; }
    }
</style>

@endsection
