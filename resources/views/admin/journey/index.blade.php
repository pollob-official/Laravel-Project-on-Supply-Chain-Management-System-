@extends('admin.layout.erp.app')
@section('content')
    <x-alert />

    <h2 class="text-success mb-2 mt-2">Product Handover History (Supply Chain)</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <!-- Left side buttons -->
            <div class="d-flex gap-2">
                <x-button :url="URL('journey/create')" type="primary">
                    <i class="bi bi-plus-lg"></i> New Handover (Entry)
                </x-button>

                <a href="{{ URL('journey/trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <!-- Right side search -->
            <form action="{{ URL('journey') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by Tracking No or Stage...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

        </div>
    </div>

    <table class="table mt-2 table-hover border">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">Tracking No</th>
                <th scope="col">Product</th>
                <th scope="col">Seller -> Buyer</th>
                <th scope="col">Prices (Buy + Cost + Profit)</th>
                <th scope="col">Selling Price</th>
                <th scope="col">Current Stage</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($journeys) > 0)
                @foreach ($journeys as $journey)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $journey->tracking_no }}</span><br>
                            <small class="text-muted">{{ $journey->created_at->format('d M, Y') }}</small>
                        </td>
                        <td>{{ $journey->product->name ?? 'N/A' }}</td>
                        <td>
                            <small class="text-success">From: {{ $journey->seller->name ?? 'N/A' }}</small><br>
                            <small class="text-info">To: {{ $journey->buyer->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <small>Buy: {{ number_format($journey->buying_price, 2) }}</small><br>
                            <small>Cost: {{ number_format($journey->extra_cost, 2) }}</small><br>
                            <small>Profit: {{ number_format($journey->profit_margin, 2) }}</small>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ number_format($journey->selling_price, 2) }} TK
                        </td>
                        <td>
                            @php
                                $stageColor =
                                    [
                                        'Farmer' => 'success',
                                        'Miller' => 'info',
                                        'Wholesaler' => 'warning',
                                        'Retailer' => 'primary',
                                    ][$journey->current_stage] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $stageColor }}">{{ $journey->current_stage }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ URL('journey/edit/' . $journey->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ URL('journey/delete/' . $journey->id) }}" method="POST"
                                    onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                    data-bs-target="#qrModal{{ $journey->id }}">
                                    <i class="bi bi-qr-code text-dark"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="qrModal{{ $journey->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header py-2">
                                            <h6 class="modal-title">QR Trace: {{ $journey->tracking_no }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center bg-white p-4"
                                            id="qrPrintArea{{ $journey->id }}">
                                            <div class="d-inline-block border p-2 mb-2 bg-white">
                                                {{-- কিউআর কোড জেনারেশন (লিঙ্কসহ) --}}
                                                {!! QrCode::size(150)->generate(url('journey/trace/' . $journey->tracking_no)) !!}
                                            </div>
                                            <h5 class="mt-2 mb-0">{{ $journey->product->name }}</h5>
                                            <p class="small text-muted mb-0">Stage: {{ $journey->current_stage }}</p>
                                            <hr>
                                            <small class="text-primary fw-bold">Scan to Verify Product History</small>
                                        </div>
                                        <div class="modal-footer py-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="printQR('qrPrintArea{{ $journey->id }}')">
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
                    <td colspan="7" class="text-center text-danger">No Handover History Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-2">
        {{ $journeys->appends(request()->query())->links() }}
    </div>

    {{-- প্রিন্ট করার জন্য জাভাস্ক্রিপ্ট --}}
    <script>
        function printQR(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML =
                "<html><head><title>Print QR</title><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'></head><body class='text-center p-5'>" +
                printContents + "</body></html>";

            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload(); // প্রিন্ট শেষে পেজ রিলোড করে স্টাইল ঠিক রাখা
        }
    </script>
@endsection
