<!-- BEGIN: SideNav-->
<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
    <div class="brand-sidebar">
        <h1 class="logo-wrapper">
            <a class="brand-logo darken-1" href="{{ url('/business') }}">
                <img class="hide-on-med-and-down " src="../../../app-assets/images/logo/materialize-logo.png" alt="materialize logo" />
                <img class="show-on-medium-and-down hide-on-med-and-up" src="../../../app-assets/images/logo/materialize-logo-color.png" alt="materialize logo" />
                <span class="logo-text hide-on-med-and-down">Admin</span>
            </a>
    </div>

    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="accordion">
        <li class="bold" style="margin-top: 3rem;">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'dashboard' ? 'active' : NULL }}" href="/business">
                <i class="material-icons">group_work</i>

                <span class="menu-title" data-i18n="Chat">მთავარი</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan {{ isset($activeMenuItem) && $activeMenuItem == 'chargerGroups' ? 'active' : NULL }}" href="/business/charger-groups">
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

        {{-- <li class="bold">
            <a class="waves-effect waves-cyan " href="user-profile-page.html">
                <i class="material-icons">person_outline</i>

                <span class="menu-title" data-i18n="User Profile">User Profile</span>
            </a>
        </li> --}}

        <li class="bold">
            <a class="waves-effect waves-cyan " href="/business/logout">
                <i class="material-icons" style="transform: rotate(180deg);">exit_to_app</i>

                <span class="menu-title" data-i18n="Chat">გამოსვლა</span>
            </a>
        </li>
    </ul>

    <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
<!-- END: SideNav-->
