@extends('admin.layout.erp.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">System Configuration</h4>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">General Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Site Name</label>
                                <input type="text" name="site_name" class="form-control" value="{{ $setting->site_name ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency Symbol</label>
                                <input type="text" name="currency_symbol" class="form-control" value="{{ $setting->currency_symbol ?? '৳' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Support Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $setting->email ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $setting->phone ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ $setting->address ?? '' }}</textarea>
                            </div>

                            {{-- লোগো সেকশন --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label d-block">Site Logo</label>
                                <input type="file" name="logo" class="form-control mb-2">
                                @if($setting && $setting->logo)
                                    <div class="p-2 border rounded bg-light d-inline-block">
                                        {{-- পাথ পরিবর্তন: storage এর বদলে assets/images --}}
                                        <img src="{{ asset('assets/images/'.$setting->logo) }}" alt="Logo" height="50">
                                    </div>
                                @endif
                            </div>

                            {{-- ফেভিকন সেকশন --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label d-block">Favicon</label>
                                <input type="file" name="favicon" class="form-control mb-2">
                                @if($setting && $setting->favicon)
                                    <div class="p-2 border rounded bg-light d-inline-block">
                                        {{-- পাথ পরিবর্তন: storage এর বদলে assets/images --}}
                                        <img src="{{ asset('assets/images/'.$setting->favicon) }}" alt="Favicon" height="30">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Footer Copyright Text</label>
                                <input type="text" name="footer_copy" class="form-control" value="{{ $setting->footer_copy ?? '' }}">
                            </div>
                        </div>
                        <div class="text-start mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-check-line me-1"></i> Update Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
