<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{route("home")}}">
                    <span class="brand-logo">
                        <img src="/app-assets/images/ico/favicon.ico" alt="Google sheet">
                    </span>
                    <h2 class="brand-text">Google sheet</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ Route::currentRouteName() == 'products' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('products.index') }}">
                    <i data-feather="archive"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Products</span>
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'google_sheets.index' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('google_sheets.index') }}">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Google Sheet</span>
                </a>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'products.fetch' ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('products.fetch') }}" target="_blank">
                    <i data-feather="list"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Google fetch</span>
                </a>
            </li>

        </ul>
    </div>
</div>
