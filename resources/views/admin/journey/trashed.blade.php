@extends("admin.layout.erp.app")
@section("content")
    <x-alert/>

    <div class="d-flex justify-content-between align-items-center mb-1 mt-2">
        <h2 class="text-danger mb-0 mt-2"><i class="bi bi-trash3"></i> Trashed Handover Records</h2>
        <a href="{{ URL('admin/journey') }}" class="btn btn-secondary shadow-sm mt-2">
            <i class="bi bi-arrow-left"></i> Back to Product Handover History
        </a>
    </div>

    <table class="table mt-2 table-hover border">
        <thead class="table-danger">
            <tr>
                <th scope="col">Tracking No</th>
                <th scope="col">Product</th>
                <th scope="col">Seller -> Buyer</th>
                <th scope="col">Deleted At</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($journeys) > 0)
                @foreach ($journeys as $journey)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $journey->tracking_no }}</span>
                        </td>
                        <td>{{ $journey->product->name ?? 'N/A' }}</td>
                        <td>
                            <small class="text-muted">From: {{ $journey->seller->name ?? 'N/A' }}</small><br>
                            <small class="text-muted">To: {{ $journey->buyer->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="text-danger">{{ $journey->deleted_at->format('d M, Y h:i A') }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Restore Button --}}
                                <a href="{{ URL('admin/journey/restore/'.$journey->id) }}" class="btn btn-sm btn-outline-success" title="Restore Data">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                </a>

                                {{-- Permanent Delete Button --}}
                                <form action="{{ URL('admin/journey/force-delete/'.$journey->id) }}" method="POST" onsubmit="return confirm('Warning! This will delete the data permanently. Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Permanent Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center text-muted py-5"> <i class="bi bi-trash fs-2"></i><br> Trash is empty!</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        {{ $journeys->links() }}
    </div>
@endsection
