<div class="leftside-menu">

    <a href="{{ URL('/') }}" class="logo logo-light text-center">
        <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" alt="logo" style="height: 60px; width: 190px;"></span>
        <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" style="height: 30px; width: 40px;"></span>
    </a>
    <a href="{{ URL('/') }}" class="logo logo-dark text-center">
        <span class="logo-lg"><img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo" style="height: 60px; width: 190px;"></span>
        <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" style="height: 30px; width: 40px;"></span>
    </a>

    <div class="user-box px-3 mt-3 mb-1">
        <div class="user-info">
            <h5 class="text-muted mb-0" style="font-size: 15px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                POLLOB AHMED SAGOR
            </h5>
            <p class="text-muted mb-0" style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                pollob.workmail@gmail.com
            </p>
        </div>
    </div>
    <hr class="mx-3 my-1 border-secondary opacity-25">

    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">

            <li class="side-nav-item">
                <a href="{{ URL('/dashboard') }}" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span> Supply Chain Overview </span>
                </a>
            </li>

            <li class="side-nav-title">MASTER CONFIGURATION</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUsers" class="side-nav-link">
                    <i class="ri-group-line"></i>
                    <span> Stakeholder Module </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarUsers">
                    <ul class="side-nav-second-level">
                        <li><a href="{{URL('admin/farmer')}}">Farmer List (Origin)</a></li>
                        <li><a href="{{URL('admin/miller')}}">Suppliers / Millers</a></li>
                        <li><a href="{{URL('admin/wholesaler')}}">Wholesalers</a></li>
                        <li><a href="{{URL('admin/retailer')}}">Retailers</a></li>
                        <li><a href="{{URL('admin/stakeholder')}}">All Stakeholders</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarProducts" class="side-nav-link">
                    <i class="ri-shopping-basket-line"></i>
                    <span> Product Master </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="side-nav-second-level">
                        <li><a href="{{URL('admin/category')}}">Categories</a></li>
                        <li><a href="{{URL('admin/product')}}">Product List</a></li>
                        <li><a href="{{URL('admin/unit')}}">Units (KG, Ton, Sack)</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title">OPERATIONS & TRACKING</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarBatches" class="side-nav-link">
                    <i class="ri-qr-code-line"></i>
                    <span> Batch & QR Engine </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBatches">
                    <ul class="side-nav-second-level">
                        <li><a href="{{URL('admin/batches/create')}}">Initiate New Batch</a></li>
                        <li><a href="{{URL('admin/batches')}}">Active Supply Batches</a></li>
                        <li><a href="{{URL('admin/batches/trashed')}}">QR Code Logs (Trash)</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarFlow" class="side-nav-link">
                    <i class="ri-route-line"></i>
                    <span> Price & Stage Flow </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarFlow">
                    <ul class="side-nav-second-level">
                        <li><a href="{{URL('admin/journey')}}">Update Stage (Handover)</a></li>
                        <li><a href="#">Cost & Profit Audit</a></li>
                        <li><a href="{{URL('admin/journey/trashed')}}">Journey History (Trash)</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title">MONITORING & REPORTS</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReports" class="side-nav-link">
                    <i class="ri-bar-chart-box-line"></i>
                    <span> Price Analytics </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarReports">
                    <ul class="side-nav-second-level">
                        <li><a href="#">Abnormal Price Alerts</a></li>
                        <li><a href="#">Price Fluctuation</a></li>
                        <li><a href="#">Supply Chain Map</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ URL('admin/settings') }}" class="side-nav-link">
                    <i class="ri-settings-line"></i>
                    <span> System Configuration </span>
                </a>
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>
