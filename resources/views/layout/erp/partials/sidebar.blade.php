<style>
/* ১. যখন বাম পাশের মেনু ছোট থাকবে তখন ইউজার বক্স এবং বর্ডার হাইড হবে */
body[data-leftbar-compact-mode="condensed"] .leftside-menu .user-box,
body[data-leftbar-compact-mode="condensed"] .leftside-menu hr {
    display: none !important;
}

/* ২. বড় স্ক্রিনে যখন নরমাল অবস্থায় থাকবে তখন দেখাবে */
.user-box {
    overflow: hidden;
    transition: all 0.3s ease;
}

/* ৩. টেক্সট যাতে এক লাইনের বাইরে গিয়ে নিচে না নামে */
.user-info h5, .user-info p {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>


<div class="leftside-menu">

    <a href="/" class="logo logo-light text-center">
        <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" alt="logo" style="height: 60px; width: 190px;"></span>
        <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" style="height: 30px; width: 40px;"></span>
    </a>
    <a href="/" class="logo logo-dark text-center">
        <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" alt="logo" style="height: 60px; width: 190px;"></span>
        <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" style="height: 30px; width: 40px;"></span>
    </a>

    <div class="user-box px-3 mt-3 mb-2">
        <div class="user-info">
            <h5 class="text-white mb-0" style="font-size: 14px; font-weight: 700; letter-spacing: 0.5px;">POLLOB AHMED SAGOR</h5>
            <p class="text-muted mb-0" style="font-size: 12px;">pollob.workmail@gmail.com</p>
        </div>
    </div>
    <hr class="mx-3 my-1 border-secondary opacity-25">

    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">

            <li class="side-nav-item">
                <a href="/" class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span> Dashboard </span>
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
                        <li><a href="{{URL("farmer")}}">Farmer List</a></li>
                        <li><a href="{{URL("miller")}}">Suppliers / Millers</a></li>
                        <li><a href="{{URL("wholesaler")}}">Wholesalers</a></li>
                        <li><a href="{{URL("retailer")}}">Retailers</a></li>
                        <li><a href="{{URL("stakeholder")}}">All Stakeholders</a></li>
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
                        <li><a href="{{URL("category")}}">Categories</a></li>
                        <li><a href="{{URL("product")}}">Product List</a></li>
                        <li><a href="{{URL("unit")}}">Units (KG, Ton, Sack)</a></li>
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
                        <li><a href="#">Generate New Batch</a></li>
                        <li><a href="#">Active Batches</a></li>
                        <li><a href="#">QR Code Logs</a></li>
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
                        <li><a href="{{URL("journey")}}">Update Stage (Handover)</a></li>
                        <li><a href="#">Cost & Profit Entry</a></li>
                        <li><a href="#">Live Tracking History</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title">REPORTS & INSIGHTS</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReports" class="side-nav-link">
                    <i class="ri-bar-chart-box-line"></i>
                    <span> Analytics Reports </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarReports">
                    <ul class="side-nav-second-level">
                        <li><a href="#">Price Fluctuation</a></li>
                        <li><a href="#">Abnormal Price Alerts</a></li>
                        <li><a href="#">Supply Chain Map</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <i class="ri-settings-line"></i>
                    <span> System Configuration </span>
                </a>
            </li>

        </ul>
        <div class="clearfix"></div>
    </div>
</div>
