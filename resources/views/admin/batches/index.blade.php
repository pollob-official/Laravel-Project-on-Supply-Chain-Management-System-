@extends('admin.layout.erp.app')
@section('page_title', 'Product Batches')

@section('content')
    <x-alert />

    <h2 class="text-success mb-2 mt-2">Smart Traceability Log (Batch Management)</h2>

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
                    name="search" placeholder="Search by Batch No or Product...">
                <button type="submit" class="btn btn-primary">Search Log</button>
            </form>
        </div>
    </div>

    <table class="table mt-2 table-hover border shadow-sm">
        <thead style="background-color:#0ae264;">
            <tr>
                <th scope="col">Batch & Seed</th>
                <th scope="col">Farmer & Location</th>
                <th scope="col">Quality & Grading</th>
                <th scope="col">Safety Status</th>
                <th scope="col">QR Tracking</th>
                <th scope="col">Dates</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @if (count($batches) > 0)
                @foreach ($batches as $batch)
                    <tr>
                        {{-- ১. ব্যাচ ও বীজের তথ্য --}}
                        <td>
                            <span class="fw-bold text-primary">{{ $batch->batch_no }}</span><br>
                            <small><strong>Seed:</strong> {{ $batch->seed_brand ?? 'N/A' }}</small>
                        </td>

                        {{-- ২. কৃষক ও লোকেশন --}}
                        <td>
                            <div class="fw-bold">{{ $batch->product->name ?? 'N/A' }}</div>
                            <small class="text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $batch->current_location ?? 'Farmer Field' }}</small>
                        </td>

                        {{-- ৩. গ্রেডিং ও কিউসি --}}
                        <td>
                            @php
                                $gradeColor = ['A' => 'success', 'B' => 'info', 'C' => 'secondary'][$batch->quality_grade] ?? 'dark';
                            @endphp
                            <span class="badge bg-{{ $gradeColor }}">Grade: {{ $batch->quality_grade ?? 'N/A' }}</span><br>
                            <small>Moisture: {{ $batch->moisture_level ?? 'N/A' }}</small>
                        </td>

                        {{-- ৪. সেফটি স্ট্যাটাস (Maturity & Pesticide Logic) --}}
                        <td>
                            @if($batch->qc_status == 'approved')
                                <span class="badge bg-success"><i class="bi bi-shield-check"></i> Safe</span>
                            @elseif($batch->qc_status == 'rejected')
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Risk</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>

                        {{-- ৫. কিউআর কোড --}}
                        <td class="text-center">
                            @if($batch->qr_code)
                                <img src="{{ asset($batch->qr_code) }}" alt="QR" width="45" class="border p-1"
                                     style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#qrModal{{ $batch->id }}">
                            @else
                                <span class="text-danger small">No QR</span>
                            @endif
                        </td>

                        {{-- ৬. তারিখ --}}
                        <td>
                            <div style="font-size: 11px;">
                                <span class="text-muted">Harvest: {{ $batch->harvest_date ? date('d M, y', strtotime($batch->harvest_date)) : 'N/A' }}</span><br>
                                <span class="text-danger">Exp: {{ $batch->expiry_date ? date('d M, y', strtotime($batch->expiry_date)) : 'N/A' }}</span>
                            </div>
                        </td>

                        {{-- ৭. অ্যাকশন --}}
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ URL('admin/batches/edit/' . $batch->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>

                                <form action="{{ URL('admin/batches/delete/' . $batch->id) }}" method="POST" onsubmit="return confirm('Move to trash?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>

                                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#qrModal{{ $batch->id }}">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>

                            {{-- QR Print Modal --}}
                            <div class="modal fade" id="qrModal{{ $batch->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body text-center bg-white p-4" id="qrPrintArea{{ $batch->id }}">
                                            <div class="d-inline-block border p-3 mb-2">
                                                <img src="{{ asset($batch->qr_code) }}" width="150">
                                            </div>
                                            <h6 class="fw-bold mb-0">{{ $batch->product->name }}</h6>
                                            <small class="text-muted">{{ $batch->batch_no }}</small>
                                            <hr class="my-2">
                                            <small class="text-success fw-bold">Scan to Trace Origin</small>
                                        </div>
                                        <div class="modal-footer py-1">
                                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="printQR('qrPrintArea{{ $batch->id }}')">Print Sticker</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="7" class="text-center py-4">No Batches Found</td></tr>
            @endif
        </tbody>
    </table>

    <div class="mt-2">{{ $batches->links() }}</div>

    <script>
        function printQR(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = "<html><head><title>Print</title><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'></head><body>" + printContents + "</body></html>";
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
@endsection
