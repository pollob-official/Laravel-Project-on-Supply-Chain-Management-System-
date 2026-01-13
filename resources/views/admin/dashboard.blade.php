@extends('admin.layout.erp.app')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">ERP</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Supply Chain Overview</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3 col-sm-6">
                    <div class="card widget-flat text-bg-primary border-0 shadow-sm">
                        <div class="card-body">
                            <div class="float-end"><i class="ri-qr-code-line widget-icon"></i></div>
                            <h6 class="text-uppercase mt-0 opacity-75">Active Batches</h6>
                            <h2 class="my-2">{{ $total_batches }}</h2>
                            <p class="mb-0"><span class="badge bg-white bg-opacity-10 me-1">Live</span> Processing</p>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card widget-flat text-white border-0 shadow-sm" style="background-color:#16a34a;">
                        <div class="card-body">
                            <div class="float-end"><i class="ri-money-dollar-circle-line widget-icon"></i></div>
                            <h6 class="text-uppercase mt-0 opacity-75">Total Revenue</h6>
                            <h2 class="my-2">{{ number_format($total_revenue, 0) }} ৳</h2>
                            <p class="mb-0"><span class="badge bg-white bg-opacity-10 me-1">Gross</span> Total Sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card widget-flat text-white border-0 shadow-sm"
                        style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
                        <div class="card-body">
                            <div class="float-end"><i class="ri-line-chart-line widget-icon"></i></div>
                            <h6 class="text-uppercase mt-0 opacity-75">Net Profit</h6>
                            <h2 class="my-2">{{ number_format($total_profit, 0) }} ৳</h2>
                            <p class="mb-0"><span class="badge bg-white bg-opacity-10 me-1">Net</span> After Expenses</p>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6">
                    <div class="card widget-flat text-bg-danger border-0 shadow-sm">
                        <div class="card-body">
                            <div class="float-end"><i class="ri-group-line widget-icon"></i></div>
                            <h6 class="text-uppercase mt-0 opacity-75">Stakeholders</h6>
                            <h2 class="my-2">{{ $total_stakeholders }}</h2>
                            <p class="mb-0"><span class="badge bg-white bg-opacity-10 me-1">Active</span> Partners</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-stretch mb-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm h-100 mb-0">
                        <div class="card-body">
                            <div class="card-widgets">
                                <a data-bs-toggle="collapse" href="#weeklysales-collapse" role="button"><i
                                        class="ri-subtract-line"></i></a>
                            </div>
                            <h5 class="header-title mb-0">Weekly Sales Report</h5>
                            <div id="weeklysales-collapse" class="collapse pt-3 show">
                                <div id="revenue-chart" class="apex-charts" data-colors="#3bc0c3,#1a2942,#d1d7d973"></div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <p class="text-muted mb-1">Current</p>
                                        <h4 class="mb-0">৳{{ number_format($total_revenue, 0) }}</h4>
                                    </div>
                                    <div class="col">
                                        <p class="text-muted mb-1">Previous</p>
                                        <h4 class="mb-0">৳0</h4>
                                    </div>
                                    <div class="col">
                                        <p class="text-muted mb-1">Growth</p>
                                        <h4 class="mb-0">3.2%</h4>
                                    </div>
                                    <div class="col">
                                        <p class="text-muted mb-1">Customers</p>
                                        <h4 class="mb-0">{{ $total_stakeholders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 mb-0">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="header-title mb-0">Yearly Performance</h5>
                                <div id="yearly-sales-chart" class="apex-charts mt-3"></div>
                            </div>
                            <div class="mt-auto">
                                <div class="row text-center mb-3">
                                    <div class="col">
                                        <p class="text-muted mb-1">Q1</p>
                                        <h5 class="mb-0">৳0</h5>
                                    </div>
                                    <div class="col">
                                        <p class="text-muted mb-1">Q2</p>
                                        <h5 class="mb-0">৳0</h5>
                                    </div>
                                    <div class="col">
                                        <p class="text-muted mb-1">All Time</p>
                                        <h5 class="mb-0">৳{{ number_format($total_revenue, 0) }}</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center border-top pt-3">
                                    <div class="flex-grow-1">
                                        <h4 class="mb-0">69.25%</h4>
                                        <p class="text-muted mb-0">System Growth</p>
                                    </div>
                                    <div id="us-share-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-stretch">
                <div class="col-xl-6">
                    <div class="card shadow-sm h-100 mb-0">
                        <div
                            class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center p-3">
                            <h5 class="header-title mb-0"><i class="ri-message-3-line me-1"></i> System Chat</h5>
                            <span class="badge bg-success-subtle text-success px-2">Online</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="chat-conversation" style="height: 350px; overflow-y: auto; padding: 20px;">
                                <ul class="conversation-list list-unstyled mb-0">
                                    <li class="clearfix mb-3">
                                        <div class="chat-avatar" style="width: 40px; float: left;">
                                            <img src="{{ asset('assets/images/users/avatar-5.jpg') }}"
                                                class="rounded-circle img-thumbnail" alt="Admin" style="width: 100%;">
                                            <small class="d-block text-center text-muted">10:00</small>
                                        </div>
                                        <div class="conversation-text ps-2" style="overflow: hidden;">
                                            <div class="ctext-wrap bg-light p-2 rounded" style="display: inline-block;">
                                                <i class="d-block fw-bold font-12 text-dark">Admin</i>
                                                <p class="mb-0 text-muted">Batch #452 update completed. Everything is on
                                                    track.</p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="clearfix mb-3 odd text-end">
                                        <div class="chat-avatar" style="width: 40px; float: right;">
                                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                                class="rounded-circle img-thumbnail" alt="User" style="width: 100%;">
                                            <small class="d-block text-center text-muted">10:01</small>
                                        </div>
                                        <div class="conversation-text pe-2" style="overflow: hidden;">
                                            <div class="ctext-wrap bg-primary text-white p-2 rounded"
                                                style="display: inline-block; text-align: left;">
                                                <i class="d-block fw-bold font-12 text-white-50">Manager</i>
                                                <p class="mb-0">Noted. Looking at the charts now. The revenue seems
                                                    higher than yesterday.</p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="clearfix mb-3">
                                        <div class="chat-avatar" style="width: 40px; float: left;">
                                            <img src="{{ asset('assets/images/users/avatar-5.jpg') }}"
                                                class="rounded-circle img-thumbnail" alt="Admin" style="width: 100%;">
                                            <small class="d-block text-center text-muted">10:05</small>
                                        </div>
                                        <div class="conversation-text ps-2" style="overflow: hidden;">
                                            <div class="ctext-wrap bg-light p-2 rounded" style="display: inline-block;">
                                                <i class="d-block fw-bold font-12 text-dark">Admin</i>
                                                <p class="mb-0 text-muted">Yes, we had a 15% increase in the morning shift
                                                    orders.</p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="clearfix mb-3 odd text-end">
                                        <div class="chat-avatar" style="width: 40px; float: right;">
                                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                                class="rounded-circle img-thumbnail" alt="User" style="width: 100%;">
                                            <small class="d-block text-center text-muted">10:06</small>
                                        </div>
                                        <div class="conversation-text pe-2" style="overflow: hidden;">
                                            <div class="ctext-wrap bg-primary text-white p-2 rounded"
                                                style="display: inline-block; text-align: left;">
                                                <i class="d-block fw-bold font-12 text-white-50">Manager</i>
                                                <p class="mb-0">Excellent! Let's prepare the weekly report for the
                                                    stakeholder meeting.</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top p-3">
                            <form id="chat-form">
                                <div class="row g-2">
                                    <div class="col"><input type="text" class="form-control border-light bg-light"
                                            placeholder="Message..." required></div>
                                    <div class="col-auto"><button type="submit" class="btn btn-primary px-3"><i
                                                class="ri-send-plane-2-line"></i></button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card shadow-sm h-100 mb-0">
                        <div class="card-header bg-transparent border-bottom p-3">
                            <h5 class="header-title mb-0"><i class="ri-flashlight-line me-1"></i> Quick Actions</h5>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-grid gap-3">
                                <a href="{{ URL('admin/batches/create') }}"
                                    class="btn btn-outline-primary btn-lg text-start p-3 border-dashed shadow-none action-card">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-sm bg-soft-primary rounded text-primary text-center"
                                            style="width: 42px; height: 42px; line-height: 42px;"><i
                                                class="ri-add-box-line fs-20"></i></div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0 fs-15">New Batch</h5><small class="text-muted">Initiate a
                                                supply chain process</small>
                                        </div>
                                        <i class="ri-arrow-right-s-line fs-20 text-muted"></i>
                                    </div>
                                </a>
                                <a href="{{ URL('admin/journey') }}"
                                    class="btn btn-outline-success btn-lg text-start p-3 border-dashed shadow-none action-card">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-sm bg-soft-success rounded text-success text-center"
                                            style="width: 42px; height: 42px; line-height: 42px;"><i
                                                class="ri-map-pin-line fs-20"></i></div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0 fs-15">Update Journey</h5><small class="text-muted">Log
                                                current product location</small>
                                        </div>
                                        <i class="ri-arrow-right-s-line fs-20 text-muted"></i>
                                    </div>
                                </a>
                                <a href="{{ URL('admin/journey/price-alerts') }}"
                                    class="btn btn-outline-danger btn-lg text-start p-3 border-dashed shadow-none action-card">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-sm bg-soft-danger rounded text-danger text-center"
                                            style="width: 42px; height: 42px; line-height: 42px;"><i
                                                class="ri-alarm-warning-line fs-20"></i></div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0 fs-15">Price Alerts</h5><small class="text-muted">Check market
                                                rate fluctuations</small>
                                        </div>
                                        <i class="ri-arrow-right-s-line fs-20 text-muted"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="alert alert-info border-0 mb-0 mt-3" role="alert"><i
                                    class="ri-information-line me-1"></i> Data refreshes every 5 mins.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .widget-flat {
            border-radius: 12px;
            transition: 0.3s;
        }

        .widget-flat:hover {
            transform: translateY(-3px);
        }

        .widget-icon {
            font-size: 32px;
            background-color: rgba(255, 255, 255, 0.2);
            height: 50px;
            width: 50px;
            text-align: center;
            line-height: 50px;
            border-radius: 8px;
            display: inline-block;
        }

        .card {
            border: none;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .header-title {
            color: #505d69;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }

        .bg-soft-primary {
            background-color: rgba(59, 113, 202, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(20, 164, 77, 0.1);
        }

        .bg-soft-danger {
            background-color: rgba(220, 76, 100, 0.1);
        }

        .action-card {
            transition: all 0.2s;
        }

        .action-card:hover {
            transform: scale(1.02);
            background-color: #fbfbfb;
        }

        .apex-charts {
            min-height: 10px !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Weekly Sales
        new ApexCharts(document.querySelector("#revenue-chart"), {
            series: [{
                name: 'Revenue',
                type: 'column',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Profit',
                type: 'line',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 320,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: [0, 3],
                curve: 'smooth'
            },
            colors: ['#3bc0c3', '#1a2942'],
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        }).render();

        // Yearly Radial
        new ApexCharts(document.querySelector("#yearly-sales-chart"), {
            series: [72],
            chart: {
                height: 230,
                type: 'radialBar'
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '65%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            fontSize: '24px',
                            show: true,
                            offsetY: 10
                        }
                    }
                }
            },
            colors: ['#3bc0c3'],
        }).render();

        // Small Share
        new ApexCharts(document.querySelector("#us-share-chart"), {
            series: [65],
            chart: {
                height: 60,
                width: 60,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            colors: ['#16a34a'],
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '40%'
                    },
                    dataLabels: {
                        show: false
                    }
                }
            }
        }).render();
    </script>
@endsection
