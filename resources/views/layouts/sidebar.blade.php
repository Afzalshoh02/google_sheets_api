<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{route('home')}}">
                    <div class="brand-logo d-flex align-items-center">
                        <img src="/app-assets/images/ico/favicon.ico" alt="Google sheet" class="rounded-circle" style="width: 32px; height: 32px;">
                        <h2 class="brand-text mb-0">Google Sheet API</h2>
                    </div>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc" data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ Route::currentRouteName() == 'products.index' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('products.index') }}">
                    <div class="menu-icon bg-light-primary rounded-circle p-1 me-2">
                        <i data-feather="archive" class="font-medium-3 text-primary"></i>
                    </div>
                    <span class="menu-title text-truncate">Products</span>
                    <span class="badge badge-light-primary rounded-pill ms-auto">New</span>
                </a>
            </li>

            <li class="nav-item {{ Route::currentRouteName() == 'google_sheets.index' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('google_sheets.index') }}">
                    <div class="menu-icon bg-light-success rounded-circle p-1 me-2">
                        <i data-feather="settings" class="font-medium-3 text-success"></i>
                    </div>
                    <span class="menu-title text-truncate">Google Sheet</span>
                </a>
            </li>

            <li class="nav-item {{ Route::currentRouteName() == 'products.fetch' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('products.fetch') }}" target="_blank">
                    <div class="menu-icon bg-light-info rounded-circle p-1 me-2">
                        <i data-feather="list" class="font-medium-3 text-info"></i>
                    </div>
                    <span class="menu-title text-truncate">Google Fetch</span>
                    <span class="badge badge-light-info rounded-pill ms-auto">3</span>
                </a>
            </li>
        </ul>
    </div>
</div>


