@extends('admin.layout.erp.app')

@section('page_title', 'Product Batches')

@section('content')
    <x-alert />

    <h2 class="text-success mb-2 mt-2">Product Batch Management (Supply Chain)</h2>

    <div class="mb-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex gap-2">
                <x-button :url="URL('admin/batches/create')" type="success">
                    <i class="bi bi-plus-lg"></i> Generate New Batch
                </x-button>

                <a href="{{ URL('admin/batches/trashed') }}" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> View Trash
                </a>
            </div>

            <form action="{{ URL('admin/batches') }}" method="GET" class="d-flex gap-1">
                <input value="{{ request('search') }}" type="text" class="form-control" style="width: 280px;"
                    name="search" placeholder="Search by Batch No...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

        </div>
    </div>

    <table class="table mt-2 table-hover border">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">Batch Info</th>
                <th scope="col">Product</th>
                <th scope="col">Initial Farmer</th>
                <th scope="col">Quantity</th>
                <th scope="col">QR Code</th>
                <th scope="col">Dates (Mfg/Exp)</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($batches) > 0)
                @foreach ($batches as $batch)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $batch->batch_no }}</span><br>
                            <small class="text-muted">ID: #{{ $batch->id }}</small>
                        </td>
                        <td>{{ $batch->product->name ?? 'N/A' }}</td>
                        <td>
                            <span class="text-dark">{{ $batch->farmer->name ?? 'N/A' }}</span><br>
                            <small class="text-muted">{{ $batch->farmer->phone ?? '' }}</small>
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $batch->total_quantity }} <small>Units</small>
                        </td>
                        <td class="text-center">
                            @if($batch->qr_code)
                                <img src="{{ asset($batch->qr_code) }}" alt="QR" width="45" class="border p-1 shadow-sm"
                                     style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#qrModal{{ $batch->id }}">
                            @else
                                <span class="text-danger">No QR</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-success">Mfg: {{ date('d M, Y', strtotime($batch->manufacturing_date)) }}</small><br>
                            <small class="text-danger">Exp: {{ $batch->expiry_date ? date('d M, Y', strtotime($batch->expiry_date)) : 'No Expiry' }}</small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ URL('admin/batches/edit/' . $batch->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ URL('admin/batches/delete/' . $batch->id) }}" method="POST"
                                    onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                    data-bs-target="#qrModal{{ $batch->id }}">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="qrModal{{ $batch->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header py-2">
                                            <h6 class="modal-title">Batch QR: {{ $batch->batch_no }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center bg-white p-4" id="qrPrintArea{{ $batch->id }}">
                                            <div class="d-inline-block border p-3 mb-2 bg-white">
                                                @if($batch->qr_code)
                                                    <img src="{{ asset($batch->qr_code) }}" width="180">
                                                @endif
                                            </div>
                                            <h5 class="mt-2 mb-0">{{ $batch->product->name ?? 'Product' }}</h5>
                                            <p class="small text-muted mb-0">Batch No: {{ $batch->batch_no }}</p>
                                            <hr>
                                            <small class="text-primary fw-bold">Traceability Guaranteed</small>
                                        </div>
                                        <div class="modal-footer py-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="printQR('qrPrintArea{{ $batch->id }}')">
                                                <i class="bi bi-printer"></i> Print QR Label
                                            </button>
                                            <a href="{{ asset($batch->qr_code) }}" download="{{ $batch->batch_no }}.png" class="btn btn-sm btn-outline-dark">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center text-danger">No Product Batches Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-2">
        {{ $batches->appends(request()->query())->links() }}
    </div>

    {{-- প্রিন্ট করার জন্য জাভাস্ক্রিপ্ট --}}
    <script>
        function printQR(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML =
                "<html><head><title>Print Batch QR</title><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'></head><body class='text-center p-5'>" +
                printContents + "</body></html>";

            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
@endsection
