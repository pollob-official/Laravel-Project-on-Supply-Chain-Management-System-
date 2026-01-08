@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-1 mt-2">
        <h2 class="mb-0 text-danger mt-2"><i class="bi bi-trash3"></i> Trashed Batches</h2>
        <a href="{{ URL('admin/batches') }}" class="btn btn-secondary shadow-sm mt-2">
            <i class="bi bi-arrow-left"></i> Back to Active Batches
        </a>
    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-danger text-dark">
                        <tr>
                            <th scope="col" style="padding-left: 20px;">#ID</th>
                            <th scope="col">Batch Info</th>
                            <th scope="col">Product</th>
                            <th scope="col">Initial Farmer</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Deleted At</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batches as $batch)
                            <tr>
                                <td style="padding-left: 20px;">{{ $batch->id }}</td>
                                <td>
                                    <strong class="text-primary">{{ $batch->batch_no }}</strong><br>
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $batch->current_location }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $batch->product->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    {{ $batch->farmer->name ?? 'Farmer ID: '.$batch->initial_farmer_id }}
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border px-2 py-1">
                                        {{ $batch->total_quantity }} Units
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger small fw-bold">
                                        <i class="bi bi-calendar-x"></i> {{ $batch->deleted_at->format('d M, Y') }}<br>
                                        <small>{{ $batch->deleted_at->format('h:i A') }}</small>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- Restore Button --}}
                                        <a href="{{ URL('admin/batches/restore/'.$batch->id) }}" class="btn btn-sm btn-outline-success px-3" title="Restore Batch">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>

                                        {{-- Permanent Delete Form --}}
                                        <form action="{{ URL('admin/batches/force_delete/'.$batch->id) }}" method="POST" onsubmit="return confirm('সাবধান! এই ব্যাচটি এবং এর সাথে সম্পর্কিত QR Code ফাইলটি চিরতরে ডিলিট হয়ে যাবে। আপনি কি নিশ্চিত?')">
                                            @csrf
                                            @method('DELETE') {{-- Controller-এ delete রিকোয়েস্ট হ্যান্ডেল করার জন্য --}}
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3" title="Permanent Delete">
                                                <i class="bi bi-x-circle"></i> Permanent
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-3">
                                        <i class="bi bi-trash fs-1 text-secondary opacity-25"></i><br>
                                        <p class="mt-2">No Trashed Batch Records Found.</p>
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
    <div class="mt-4 d-flex justify-content-center">
        {{ $batches->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endsection
