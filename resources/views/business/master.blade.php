<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="author" content="Redberry">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Espace {{ isset($tabTitle) ? ' | ' . $tabTitle : NULL }}</title>
	<link rel="apple-touch-icon" href="../../../app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/favicon/favicon-32x32.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/vendors.min.css">
    <!-- END: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/style-rtl.css">
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/themes/horizontal-menu-template/materialize.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/themes/horizontal-menu-template/style.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/layouts/style-horizontal.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/pages/dashboard.css">
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/custom/custom.css">
    <!-- END: Custom CSS-->
<head>

</head>
<body>
    <!-- BEGIN: Header-->
    <header class="page-topbar" id="header">
        <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-light-blue-cyan">
                <div class="nav-wrapper">
                    <ul class="left">
                        <li>
                            <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="index.html"><img src="../../../app-assets/images/logo/materialize-logo.png" alt="materialize logo"><span class="logo-text hide-on-med-and-down">Materialize</span></a></h1>
                        </li>
                    </ul>
                    <div class="header-search-wrapper hide-on-med-and-down"><i class="material-icons">search</i>
                        <input class="header-search-input z-depth-2" type="text" name="Search" placeholder="Explore Materialize" data-search="template-list">
                        <ul class="search-list collection display-none"></ul>
                    </div>
                    <ul class="navbar-list right">
                        <li class="dropdown-language"><a class="waves-effect waves-block waves-light translation-button" href="javascript:void(0);" data-target="translation-dropdown"><span class="flag-icon flag-icon-gb"></span></a></li>
                        <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a></li>
                        <li class="hide-on-large-only"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search </i></a></li>
                        <li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge orange accent-3">5</small></i></a></li>
                        <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar"><i></i></span></a></li>
                        <li><a class="waves-effect waves-block waves-light sidenav-trigger" href="#" data-target="slide-out-right"><i class="material-icons">format_indent_increase</i></a></li>
                    </ul>
                    <!-- translation-button-->
                    <ul class="dropdown-content" id="translation-dropdown">
                        <li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="en"><i class="flag-icon flag-icon-gb"></i> English</a></li>
                        <li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="fr"><i class="flag-icon flag-icon-fr"></i> French</a></li>
                        <li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></li>
                        <li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="de"><i class="flag-icon flag-icon-de"></i> German</a></li>
                    </ul>
                    <!-- notifications-dropdown-->
                    <ul class="dropdown-content" id="notifications-dropdown">
                        <li>
                            <h6>NOTIFICATIONS<span class="new badge">5</span></h6>
                        </li>
                        <li class="divider"></li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle cyan small">add_shopping_cart</span> A new order has been placed!</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">2 hours ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle red small">stars</span> Completed the task</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">3 days ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle teal small">settings</span> Settings updated</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">4 days ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle deep-orange small">today</span> Director meeting started</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">6 days ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle amber small">trending_up</span> Generate monthly report</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">1 week ago</time>
                        </li>
                    </ul>
                    <!-- profile-dropdown-->
                    <ul class="dropdown-content" id="profile-dropdown">
                        <li><a class="grey-text text-darken-1" href="user-profile-page.html"><i class="material-icons">person_outline</i> Profile</a></li>
                        <li><a class="grey-text text-darken-1" href="app-chat.html"><i class="material-icons">chat_bubble_outline</i> Chat</a></li>
                        <li><a class="grey-text text-darken-1" href="page-faq.html"><i class="material-icons">help_outline</i> Help</a></li>
                        <li class="divider"></li>
                        <li><a class="grey-text text-darken-1" href="user-lock-screen.html"><i class="material-icons">lock_outline</i> Lock</a></li>
                        <li><a class="grey-text text-darken-1" href="user-login.html"><i class="material-icons">keyboard_tab</i> Logout</a></li>
                    </ul>
                </div>
                <nav class="display-none search-sm">
                    <div class="nav-wrapper">
                        <form>
                            <div class="input-field search-input-sm">
                                <input class="search-box-sm" type="search" required="" id="search" data-search="template-list">
                                <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                                <ul class="search-list collection search-list-sm display-none"></ul>
                            </div>
                        </form>
                    </div>
                </nav>
            </nav>
            <!-- BEGIN: Horizontal nav start-->
            <nav class="white hide-on-med-and-down" id="horizontal-nav">
                <div class="nav-wrapper">
                    <ul class="left hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation">
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="DashboardDropdown"><i class="material-icons">dashboard</i><span><span class="dropdown-title" data-i18n="Dashboard">Dashboard</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="DashboardDropdown">
                                <li data-menu=""><a href="dashboard-modern.html"><span data-i18n="Modern">Modern</span></a>
                                </li>
                                <li class="active" data-menu=""><a href="dashboard-ecommerce.html"><span data-i18n="eCommerce">eCommerce</span></a>
                                </li>
                                <li data-menu=""><a href="dashboard-analytics.html"><span data-i18n="Analytics">Analytics</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="TemplatesDropdown"><i class="material-icons">dvr</i><span><span class="dropdown-title" data-i18n="Templates">Templates</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="TemplatesDropdown">
                                <li data-menu=""><a href="../vertical-modern-menu-template/"><span data-i18n="Modern Menu">Modern Menu</span></a>
                                </li>
                                <li data-menu=""><a href="../vertical-menu-nav-dark-template/"><span data-i18n="Navbar Dark">Navbar Dark</span></a>
                                </li>
                                <li data-menu=""><a href="../vertical-gradient-menu-template/"><span data-i18n="Gradient Menu">Gradient Menu</span></a>
                                </li>
                                <li data-menu=""><a href="../vertical-dark-menu-template/"><span data-i18n="Dark Menu">Dark Menu</span></a>
                                </li>
                                <li data-menu=""><a href="../horizontal-menu-template/"><span data-i18n="Horizontal Menu">Horizontal Menu</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="AppsDropdown"><i class="material-icons">mail_outline</i><span><span class="dropdown-title" data-i18n="Apps">Apps</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="AppsDropdown">
                                <li data-menu=""><a href="app-email.html"><span data-i18n="Mail">Mail</span></a>
                                </li>
                                <li data-menu=""><a href="app-chat.html"><span data-i18n="Chat">Chat</span></a>
                                </li>
                                <li data-menu=""><a href="app-todo.html"><span data-i18n="ToDo">ToDo</span></a>
                                </li>
                                <li data-menu=""><a href="app-contacts.html"><span data-i18n="Contacts">Contacts</span></a>
                                </li>
                                <li data-menu=""><a href="app-calendar.html"><span data-i18n="Calendar">Calendar</span></a>
                                </li>
                                <li data-menu=""><a href="app-kanban.html"><span data-i18n="Kanban">Kanban</span></a>
                                </li>
                                <li data-menu=""><a href="app-file-manager.html"><span data-i18n="File Manager">File manager</span></a>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="invoiceDropdown"><span data-i18n="Invoice">Invoice</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="invoiceDropdown">
                                        <li data-menu=""><a href="app-invoice-list.html"><span data-i18n="Invoice List">Invoice List</span></a>
                                        </li>
                                        <li data-menu=""><a href="app-invoice-view.html"><span data-i18n="Invoice View">Invoice View</span></a>
                                        </li>
                                        <li data-menu=""><a href="app-invoice-edit.html"><span data-i18n="Invoice Edit">Invoice Edit</span></a>
                                        </li>
                                        <li data-menu=""><a href="app-invoice-add.html"><span data-i18n="Invoice Add">Invoice Add</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="eCommerceDropdown"><span data-i18n="eCommerce">eCommerce</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="eCommerceDropdown">
                                        <li data-menu=""><a href="eCommerce-products-page.html"><span data-i18n="Products Page">Products Page</span></a>
                                        </li>
                                        <li data-menu=""><a href="eCommerce-pricing.html"><span data-i18n="Pricing">Pricing</span></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="PageDropdown"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Pages">Pages</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="PageDropdown">
                                <li data-menu=""><a href="page-contact.html"><span data-i18n="Contact">Contact</span></a>
                                </li>
                                <li data-menu=""><a href="page-blog-list.html"><span data-i18n="Blog">Blog</span></a>
                                </li>
                                <li data-menu=""><a href="page-search.html"><span data-i18n="Search">Search</span></a>
                                </li>
                                <li data-menu=""><a href="page-knowledge.html"><span data-i18n="Knowledge">Knowledge</span></a>
                                </li>
                                <li data-menu=""><a href="page-timeline.html"><span data-i18n="Timeline">Timeline</span></a>
                                </li>
                                <li data-menu=""><a href="page-faq.html"><span data-i18n="FAQs">FAQs</span></a>
                                </li>
                                <li data-menu=""><a href="page-blank.html"><span data-i18n="Page Blank">Page Blank</span></a>
                                </li>
                                <li data-menu=""><a href="user-profile-page.html"><span data-i18n="User Profile">User Profile</span></a>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="userDropdown"><span data-i18n="User">User</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="userDropdown">
                                        <li data-menu=""><a href="page-users-list.html"><span data-i18n="List">List</span></a>
                                        </li>
                                        <li data-menu=""><a href="page-users-view.html"><span data-i18n="View">View</span></a>
                                        </li>
                                        <li data-menu=""><a href="page-users-edit.html"><span data-i18n="Edit">Edit</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="mediasDropdown"><span data-i18n="Medias">Medias</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="mediasDropdown">
                                        <li data-menu=""><a href="media-gallery-page.html"><span data-i18n="Gallery Page">Gallery Page</span></a>
                                        </li>
                                        <li data-menu=""><a href="media-hover-effects.html"><span data-i18n="Media Hover Effects">Media Hover Effects</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="authDropdown"><span data-i18n="Authentication">Authentication</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="authDropdown">
                                        <li data-menu=""><a href="user-login.html" target="_blank"><span data-i18n="Login">Login</span></a>
                                        </li>
                                        <li data-menu=""><a href="user-register.html" target="_blank"><span data-i18n="Register">Register</span></a>
                                        </li>
                                        <li data-menu=""><a href="user-forgot-password.html" target="_blank"><span data-i18n="Forgot Password">Forgot Password</span></a>
                                        </li>
                                        <li data-menu=""><a href="user-lock-screen.html" target="_blank"><span data-i18n="Lock Screen">Lock Screen</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="miscDropdown"><span data-i18n="Misc">Misc</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="miscDropdown">
                                        <li data-menu=""><a href="page-404.html" target="_blank"><span data-i18n="404">404</span></a>
                                        </li>
                                        <li data-menu=""><a href="page-500.html" target="_blank"><span data-i18n="500">500</span></a>
                                        </li>
                                        <li data-menu=""><a href="page-maintenance.html" target="_blank"><span data-i18n="Page Maintenanace">Page Maintenanace</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li data-menu=""><a href="page-account-settings.html"><span data-i18n="Account Settings">Account Settings</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="Cards"><i class="material-icons">cast</i><span><span class="dropdown-title" data-i18n="Cards">Cards</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="Cards">
                                <li data-menu=""><a href="cards-basic.html"><span data-i18n="Cards">Cards</span></a>
                                </li>
                                <li data-menu=""><a href="cards-advance.html"><span data-i18n="Cards Advance">Cards Advance</span></a>
                                </li>
                                <li data-menu=""><a href="cards-extended.html"><span data-i18n="Cards Extended">Cards Extended</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="CssDropdown"><i class="material-icons">invert_colors</i><span><span class="dropdown-title" data-i18n="CSS">CSS</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="CssDropdown">
                                <li data-menu=""><a href="css-typography.html"><span data-i18n="Typograpy">Typograpy</span></a>
                                </li>
                                <li data-menu=""><a href="css-color.html"><span data-i18n="Color">Color</span></a>
                                </li>
                                <li data-menu=""><a href="css-grid.html"><span data-i18n="Grid">Grid</span></a>
                                </li>
                                <li data-menu=""><a href="css-helpers.html"><span data-i18n="Helpers">Helpers</span></a>
                                </li>
                                <li data-menu=""><a href="css-media.html"><span data-i18n="Media">Media</span></a>
                                </li>
                                <li data-menu=""><a href="css-pulse.html"><span data-i18n="Pulse">Pulse</span></a>
                                </li>
                                <li data-menu=""><a href="css-sass.html"><span data-i18n="Sass">Sass</span></a>
                                </li>
                                <li data-menu=""><a href="css-shadow.html"><span data-i18n="Shadow">Shadow</span></a>
                                </li>
                                <li data-menu=""><a href="css-animations.html"><span data-i18n="Animations">Animations</span></a>
                                </li>
                                <li data-menu=""><a href="css-transitions.html"><span data-i18n="Transitions">Transitions</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="BasicUIDropdown"><i class="material-icons">photo_filter</i><span><span class="dropdown-title" data-i18n="Basic UI">Basic UI</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="BasicUIDropdown">
                                <li data-menu=""><a href="ui-basic-buttons.html"><span data-i18n="Basic Buttons">Basic Buttons</span></a>
                                </li>
                                <li data-menu=""><a href="ui-extended-buttons.html"><span data-i18n="Extended Buttons">Extended Buttons</span></a>
                                </li>
                                <li data-menu=""><a href="ui-icons.html"><span data-i18n="Icons">Icons</span></a>
                                </li>
                                <li data-menu=""><a href="ui-alerts.html"><span data-i18n="Alerts">Alerts</span></a>
                                </li>
                                <li data-menu=""><a href="ui-badges.html"><span data-i18n="Badges">Badges</span></a>
                                </li>
                                <li data-menu=""><a href="ui-breadcrumbs.html"><span data-i18n="Breadcrumbs">Breadcrumbs</span></a>
                                </li>
                                <li data-menu=""><a href="ui-chips.html"><span data-i18n="Chips">Chips</span></a>
                                </li>
                                <li data-menu=""><a href="ui-collections.html"><span data-i18n="Collections">Collections</span></a>
                                </li>
                                <li data-menu=""><a href="ui-navbar.html"><span data-i18n="Navbar">Navbar</span></a>
                                </li>
                                <li data-menu=""><a href="ui-pagination.html"><span data-i18n="Pagination">Pagination</span></a>
                                </li>
                                <li data-menu=""><a href="ui-preloader.html"><span data-i18n="Preloader">Preloader</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="AdvancedUIDropdown"><i class="material-icons">settings_brightness</i><span><span class="dropdown-title" data-i18n="Advanced UI">Advanced UI</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="AdvancedUIDropdown">
                                <li data-menu=""><a href="advance-ui-carousel.html"><span data-i18n="Carousel">Carousel</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-collapsibles.html"><span data-i18n="Collapsibles">Collapsibles</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-toasts.html"><span data-i18n="Toasts">Toasts</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-tooltip.html"><span data-i18n="Tooltip">Tooltip</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-dropdown.html"><span data-i18n="Dropdown">Dropdown</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-feature-discovery.html"><span data-i18n="Discovery">Discovery</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-media.html"><span data-i18n="Media">Media</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-modals.html"><span data-i18n="Modals">Modals</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-scrollspy.html"><span data-i18n="Scrollspy">Scrollspy</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-tabs.html"><span data-i18n="Tabs">Tabs</span></a>
                                </li>
                                <li data-menu=""><a href="advance-ui-waves.html"><span data-i18n="Waves">Waves</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="ExtraComponentsDropdown"><i class="material-icons">add_to_queue</i><span><span class="dropdown-title" data-i18n="Extra Components">Extra Components</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="ExtraComponentsDropdown">
                                <li data-menu=""><a href="extra-components-range-slider.html"><span data-i18n="Range Slider">Range Slider</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-sweetalert.html"><span data-i18n="Sweetalert">Sweetalert</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-nestable.html"><span data-i18n="Nestable">Nestable</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-treeview.html"><span data-i18n="Treeview">Treeview</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-ratings.html"><span data-i18n="Ratings">Ratings</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-tour.html"><span data-i18n="Tour">Tour</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-i18n.html"><span data-i18n="i18n">i18n</span></a>
                                </li>
                                <li data-menu=""><a href="extra-components-highlight.html"><span data-i18n="Highlight">Highlight</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="formsTables"><i class="material-icons">chrome_reader_mode</i><span><span class="dropdown-title" data-i18n="Forms &amp; Tables">Forms &amp; Tables</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="formsTables">
                                <li data-menu=""><a href="table-basic.html"><span data-i18n="Basic Tables">Basic Tables</span></a>
                                </li>
                                <li data-menu=""><a href="table-data-table.html"><span data-i18n="Data Tables">Data Tables</span></a>
                                </li>
                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdownSub-menu" href="Javascript:void(0)" data-target="formsDropdown"><span data-i18n="Forms">Forms</span><i class="material-icons right">chevron_right</i></a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="formsDropdown">
                                        <li data-menu=""><a href="form-elements.html"><span data-i18n="Form Elements">Form Elements</span></a>
                                        </li>
                                        <li data-menu=""><a href="form-select2.html"><span data-i18n="Form Select2">Form Select2</span></a>
                                        </li>
                                        <li data-menu=""><a href="form-validation.html"><span data-i18n="Form Validation">Form Validation</span></a>
                                        </li>
                                        <li data-menu=""><a href="form-masks.html"><span data-i18n="Form Masks">Form Masks</span></a>
                                        </li>
                                        <li data-menu=""><a href="form-editor.html"><span data-i18n="Form Editor">Form Editor</span></a>
                                        </li>
                                        <li data-menu=""><a href="form-file-uploads.html"><span data-i18n="File Uploads">File Uploads</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li data-menu=""><a href="form-layouts.html"><span data-i18n="Form Layouts">Form Layouts</span></a>
                                </li>
                                <li data-menu=""><a href="form-wizard.html"><span data-i18n="Form Wizard">Form Wizard</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-menu" href="Javascript:void(0)" data-target="ChartDropdown"><i class="material-icons">pie_chart_outlined</i><span><span class="dropdown-title" data-i18n="Chart">Chart</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="ChartDropdown">
                                <li data-menu=""><a href="charts-chartjs.html"><span data-i18n="ChartJS">ChartJS</span></a>
                                </li>
                                <li data-menu=""><a href="charts-chartist.html"><span data-i18n="Chartist">Chartist</span></a>
                                </li>
                                <li data-menu=""><a href="charts-sparklines.html"><span data-i18n="Sparkline Charts">Sparkline Charts</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- END: Horizontal nav start-->
            </nav>
        </div>
    </header>
    <!-- END: Header-->
    <!-- BEGIN: Page Main-->
    <div id="main">
    	@yield('body')
    </div>
    <!-- END: Page Main-->
    <!-- BEGIN: Footer-->
    <footer class="page-footer footer footer-static footer-dark gradient-45deg-light-blue-cyan gradient-shadow navbar-border navbar-shadow">
        <div class="footer-copyright">
            <div class="container"><span>&copy; 2019 <a href="http://themeforest.net/user/pixinvent/portfolio?ref=pixinvent" target="_blank">PIXINVENT</a> All rights reserved.</span><span class="right hide-on-small-only">Design and Developed by <a href="https://pixinvent.com/">PIXINVENT</a></span></div>
        </div>
    </footer>

    <!-- END: Footer-->
	
  <!-- BEGIN VENDOR JS-->
    <script src="../../../app-assets/js/vendors.min.js"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="../../../app-assets/vendors/chartjs/chart.min.js"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="../../../app-assets/js/plugins.js"></script>
    <script src="../../../app-assets/js/search.js"></script>
    <script src="../../../app-assets/js/custom/custom-script.js"></script>
    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="../../../app-assets/js/scripts/dashboard-ecommerce.js"></script>
    <!-- END PAGE LEVEL JS-->
</body>
</html>