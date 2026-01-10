@extends('admin.layout.erp.app')
@section('content')
    <x-alert />

    <h2 class="text-success mb-2 mt-2"><i class="ri-history-line me-2"></i>Product Handover History (Supply Chain)</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex gap-2">
                <x-button :url="URL('admin/journey/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> New Handover
                </x-button>

                <a href="{{ URL('admin/journey/trashed') }}" class="btn btn-outline-danger shadow-sm">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <form action="{{ URL('admin/journey') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control shadow-sm" style="width: 280px;"
                    name="search" placeholder="Search Tracking or Batch...">
                <button type="submit" class="btn btn-primary shadow-sm">Search</button>
            </form>
        </div>
    </div>

    <table class="table mt-2 table-hover border bg-white shadow-sm align-middle">
        <thead class="table-success">
            <tr>
                <th scope="col">Tracking / Batch</th>
                <th scope="col">Product Info</th>
                <th scope="col">Seller -> Buyer</th>
                <th scope="col">Breakdown</th>
                <th scope="col">Selling Price</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($journeys) > 0)
                @foreach ($journeys as $journey)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $journey->tracking_no }}</span><br>
                            <span class="badge bg-light text-dark border">Batch: {{ $journey->batch->batch_code ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $journey->product->name ?? 'N/A' }}</span><br>
                            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $journey->location ?? 'No Location' }}</small>
                        </td>
                        <td>
                            <div class="d-flex flex-column" style="font-size: 0.85rem;">
                                <span class="text-danger"><i class="bi bi-arrow-up-circle"></i> {{ $journey->seller->name ?? 'N/A' }}</span>
                                <span class="text-success"><i class="bi bi-arrow-down-circle"></i> {{ $journey->buyer->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.8rem;">
                            <div class="text-muted">Buy: {{ number_format($journey->buying_price, 2) }}</div>
                            <div class="text-muted">Cost: {{ number_format($journey->extra_cost, 2) }}</div>
                            <div class="text-warning fw-bold">Profit: {{ number_format($journey->profit_margin, 2) }}</div>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ number_format($journey->selling_price, 2) }} ৳
                        </td>
                        <td>
                            @php
                                $stageColor = [
                                    'Farmer' => 'success',
                                    'Miller' => 'info',
                                    'Wholesaler' => 'warning',
                                    'Retailer' => 'primary',
                                ][$journey->current_stage] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $stageColor }} mb-1 d-block">{{ $journey->current_stage }}</span>
                            <small class="text-muted border-bottom">{{ $journey->quality_status }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                {{-- New Trace View Button --}}
                                <a href="{{ URL('admin/journey/trace/' . $journey->tracking_no) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Trace Page">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ URL('admin/journey/edit/' . $journey->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ URL('admin/journey/delete/' . $journey->id) }}" method="POST"
                                    onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#qrModal{{ $journey->id }}" title="Show QR">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                            </div>

                            {{-- QR Modal remains same... --}}
                            <div class="modal fade" id="qrModal{{ $journey->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header py-2">
                                            <h6 class="modal-title">QR Trace ID: {{ $journey->tracking_no }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center bg-white p-4" id="qrPrintArea{{ $journey->id }}">
                                            <div class="d-inline-block border p-2 mb-2 bg-white">
                                                {!! QrCode::size(150)->generate(url('admin/journey/trace/' . $journey->tracking_no)) !!}
                                            </div>
                                            <h5 class="mt-2 mb-0">{{ $journey->product->name }}</h5>
                                            <p class="small text-muted mb-0">Batch: {{ $journey->batch->batch_code ?? 'N/A' }}</p>
                                            <hr>
                                            <small class="text-primary fw-bold">Scan to Verify History</small>
                                        </div>
                                        <div class="modal-footer py-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="printQR('qrPrintArea{{ $journey->id }}')">
                                                <i class="bi bi-printer"></i> Print QR
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center text-danger p-4">No Handover History Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-2">
        {{ $journeys->appends(request()->query())->links() }}
    </div>

    <script>
        function printQR(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = "<html><head><title>Print QR</title><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'></head><body class='text-center p-5'>" + printContents + "</body></html>";
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
@endsection
