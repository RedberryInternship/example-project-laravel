<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
    <div class="espace-brand-wrapper">
            <a href="{{ url('/business') }}">
                <img src="/images/original-logo.png" class="espace-brand">
            </a>
            <h1>Business Admin</h1>
            <hr>
            <h1 class="business-title">
                <i class="material-icons business-icon">business_center</i>
                {{ $companyName }}
            </h1>
    </div>

    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="accordion">
        <li class="bold" style="margin-top: 3rem;">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'dashboard' ? 'active' : NULL }}" href="/business">
                <i class="material-icons">dashboard</i>

                <span class="menu-title" data-i18n="Chat">მთავარი</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'orders' ? 'active' : NULL }}" href="/business/orders">
                <i class="material-icons">payment</i>

                <span class="menu-title" data-i18n="Chat">ტრანზაქციები</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'groups' ? 'active' : NULL }}" href="/business/groups">
                <i class="material-icons">group_work</i>

                <span class="menu-title" data-i18n="Chat">დამტენების ჯგუფები</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'chargers' ? 'active' : NULL }}" href="/business/chargers">
                <i class="material-icons">battery_charging_full</i>

                <span class="menu-title" data-i18n="Chat">დამტენები</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'profile' ? 'active' : NULL }}" href="/business/profile">
                <i class="material-icons">person</i>

                <span class="menu-title" data-i18n="Chat">პროფილი</span>
            </a>
        </li>

        <li class="bold sidebar-logout-menu-item">
            <a class="waves-effect waves-cyan logout-from-business-admin">
                <i class="material-icons" style="transform: rotate(180deg);">exit_to_app</i>

                <span class="menu-title" data-i18n="Chat">გამოსვლა</span>
            </a>
        </li>
    </ul>

    <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
