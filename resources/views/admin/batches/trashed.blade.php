@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 text-danger fw-bold">
                    <i class="bi bi-trash3-fill"></i> Trashed Product Batches
                </h2>
                <p class="text-muted small mb-0">Manage and recover deleted batch records from the archive.</p>
            </div>
            <a href="{{ URL('admin/batches') }}" class="btn btn-outline-secondary shadow-sm rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Back to Active Batches
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-danger-subtle text-dark">
                            <tr>
                                <th scope="col" class="ps-4 py-3">#ID</th>
                                <th scope="col">Batch & Location</th>
                                <th scope="col">Product Information</th>
                                <th scope="col">Farmer Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Deletion Timeline</th>
                                <th scope="col" class="text-center pe-4">Recovery Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batches as $batch)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">{{ $batch->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-primary">{{ $batch->batch_no }}</span>
                                            <small class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $batch->current_location ?? 'No Location' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark-subtle text-dark border px-2">
                                            {{ $batch->product->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $batch->farmer->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $batch->farmer->phone ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ number_format($batch->total_quantity, 2) }} <small>Units</small>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-danger small fw-bold">
                                            <i class="bi bi-calendar-x"></i> {{ $batch->deleted_at->format('d M, Y') }}
                                        </div>
                                        <div class="text-muted x-small" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock"></i> {{ $batch->deleted_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-2 justify-content-center">
                                            {{-- Restore Button --}}
                                            <a href="{{ URL('admin/batches/restore/'.$batch->id) }}"
                                               class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                               title="Restore Batch">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </a>

                                            {{-- Permanent Delete Form --}}
                                            <form action="{{ URL('admin/batches/force_delete/'.$batch->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('সাবধান! এই ব্যাচটি চিরতরে ডিলিট হয়ে যাবে এবং এটি আর পুনরুদ্ধার করা সম্ভব হবে না। আপনি কি নিশ্চিত?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Permanent Delete">
                                                    <i class="bi bi-x-circle"></i> Permanent
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-recycle fs-1 text-secondary opacity-25"></i>
                                            <h5 class="mt-3 text-muted">Trash is Empty</h5>
                                            <p class="text-secondary small">No deleted batch records found in the system.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination Section --}}
        @if($batches->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            <div class="shadow-sm p-2 bg-white rounded-pill">
                {{ $batches->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>

    <style>
        .table thead th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .table tbody td {
            font-size: 0.95rem;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .rounded-4 { border-radius: 1rem !important; }
        .bg-danger-subtle { background-color: #f8d7da !important; }
        .x-small { font-size: 0.8rem; }
    </style>
@endsection
