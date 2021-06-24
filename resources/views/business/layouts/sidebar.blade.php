<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
    <div class="espace-brand-wrapper">
            <a href="{{ url('/business') }}">
                <img src="/images/original-logo.png" class="espace-brand">
            </a>
            <h1>Business Admin</h1>
            <hr>
            <h1 class="business-title">
                <img class="espace-icon" src="{{ asset('images/business/icons/business.png') }}" />
                {{ $companyName }}
            </h1>
    </div>

    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="accordion">
        <li class="bold" style="margin-top: 5rem;">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'dashboard' ? 'active' : NULL }}" href="/business">
                <img class="espace-icon" src="{{ asset('images/business/icons/dashboard.png') }}" />
                <span class="menu-title bpg-arial" data-i18n="Chat">@lang('business.sidebar.main')</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'orders' ? 'active' : NULL }}" href="/business/orders">
                <img class="espace-icon" src="{{ asset('images/business/icons/transactions.png') }}" />

                <span class="menu-title bpg-arial" data-i18n="Chat">@lang('business.sidebar.transactions')</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'groups' ? 'active' : NULL }}" href="/business/groups">
                <img class="espace-icon" src="{{ asset('images/business/icons/groups.png') }}" />

                <span class="menu-title bpg-arial" data-i18n="Chat">@lang('business.sidebar.charger-groups')</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'chargers' ? 'active' : NULL }}" href="/business/chargers">
                <img class="espace-icon" src="{{ asset('images/business/icons/chargers.png') }}" />
                <span class="menu-title bpg-arial" data-i18n="Chat">@lang('business.sidebar.chargers')</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'profile' ? 'active' : NULL }}" href="/business/profile">
                <img class="espace-icon" src="{{ asset('images/business/icons/profile.png') }}" />
                <span class="menu-title bpg-arial" data-i18n="Chat">@lang('business.sidebar.profile')</span>
            </a>
        </li>

        <li class="bold lang-switcher-container">
        </li>


        <li class="bold sidebar-logout-menu-item">
            <div class="lang-wrapper">
                <a href="{{ route('business.change-language', [ 'lang' => 'ka']) }}">
                    <span class="lang-item @selectedlang('ka') active @endselectedlang">Ka</span>
                </a>

                <a href="{{ route('business.change-language', [ 'lang' => 'en']) }}">
                    <span class="lang-item @selectedlang('en') active @endselectedlang">En</span>
                </a>
            </div>
            <a class="waves-effect waves-cyan logout-from-business-admin">
                <img class="espace-icon" src="{{ asset('images/business/icons/logout.png') }}" />

                <span class="menu-title bpg-arial" data-i18n="Chat">@lang('business.sidebar.logout')</span>
            </a>
        </li>
    </ul>

    <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
