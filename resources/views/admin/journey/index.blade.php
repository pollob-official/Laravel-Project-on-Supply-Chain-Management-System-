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
                <th scope="col">Price Breakdown</th>
                <th scope="col">Selling Price</th>
                <th scope="col">Status & Quality</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($journeys) > 0)
                @foreach ($journeys as $journey)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $journey->tracking_no }}</span><br>
                            <span class="badge bg-light text-dark border">Batch: {{ $journey->batch->batch_no ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $journey->product->name ?? 'N/A' }}</span><br>
                            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $journey->location ?? 'No Location' }}</small>
                        </td>
                        <td>
                            <div class="d-flex flex-column" style="font-size: 0.85rem;">
                                <span class="text-danger fw-medium"><i class="bi bi-arrow-up-circle"></i> {{ $journey->seller->name ?? 'N/A' }}</span>
                                <span class="text-success fw-medium"><i class="bi bi-arrow-down-circle"></i> {{ $journey->buyer->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.8rem;">
                            <div class="text-muted">Buy: {{ number_format($journey->buying_price, 2) }}</div>
                            <div class="text-muted">Cost: {{ number_format($journey->extra_cost, 2) }}</div>
                            <div class="text-warning fw-bold">Profit: {{ number_format($journey->profit_margin, 2) }}</div>
                        </td>
                        <td class="fw-bold text-dark text-nowrap">
                            {{ number_format($journey->selling_price, 2) }} ৳
                        </td>
                        <td>
                            @php
                                $stageColors = [
                                    'Farmer' => 'success',
                                    'Miller' => 'info',
                                    'Wholesaler' => 'warning',
                                    'Retailer' => 'primary',
                                ];
                                $color = $stageColors[$journey->current_stage] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} mb-1 d-block px-2 py-1">{{ $journey->current_stage }}</span>

                            {{-- Authentic Detail: Quality Badge with logic --}}
                            @if($journey->quality_status)
                                <div class="text-center py-1 border rounded bg-light" style="font-size: 10px;">
                                    <span class="text-muted">Grade:</span>
                                    <span class="fw-bold {{ strtolower($journey->quality_status) == 'good' || strtolower($journey->quality_status) == 'passed' ? 'text-success' : 'text-danger' }}">
                                        {{ $journey->quality_status }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ URL('admin/journey/trace/' . $journey->tracking_no) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Trace">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ URL('admin/journey/edit/' . $journey->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ URL('admin/journey/delete/' . $journey->id) }}" method="POST"
                                    onsubmit="return confirm('Move to trash?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <button class="btn btn-sm btn-dark shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#qrModal{{ $journey->id }}" title="Show QR">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                            </div>

                            {{-- QR Modal --}}
                            <div class="modal fade" id="qrModal{{ $journey->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header py-2 bg-light">
                                            <h6 class="modal-title small">Tracking: {{ $journey->tracking_no }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center bg-white p-4" id="qrPrintArea{{ $journey->id }}">
                                            <div class="d-inline-block border p-2 mb-2 bg-white shadow-sm">
                                                {!! QrCode::size(150)->generate(url('admin/journey/trace/' . $journey->tracking_no)) !!}
                                            </div>
                                            <h5 class="mt-2 mb-0 fw-bold">{{ $journey->product->name }}</h5>
                                            <p class="small text-muted mb-0">Batch: {{ $journey->batch->batch_no ?? 'N/A' }}</p>
                                            <hr class="my-2">
                                            <small class="text-primary fw-bold">Authenticity Guaranteed</small>
                                        </div>
                                        <div class="modal-footer py-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="printQR('qrPrintArea{{ $journey->id }}')">
                                                <i class="bi bi-printer"></i> Print QR Label
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
