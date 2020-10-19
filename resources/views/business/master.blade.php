<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <!-- BEGIN: Head-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
        <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
        <meta name="author" content="ThemeSelect">
        <meta name="_token" content="{{ csrf_token() }}">

        <title>E-space {{ isset($tabTitle) ? ' | ' . $tabTitle : NULL }}</title>

        <link rel="apple-touch-icon" href="/images/favicon.png">
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- BEGIN: VENDOR CSS-->
        <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/vendors.min.css">
        <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/select2/select2.min.css">
        <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/select2/select2-materialize.css">
        <!-- END: VENDOR CSS-->
        <!-- BEGIN: Page Level CSS-->
        <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/vertical-dark-menu-template/materialize.css">
        <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/vertical-dark-menu-template/style.css">
        <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/dashboard.css">
        <!-- END: Page Level CSS-->
        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom/style.css">
        <!-- END: Custom CSS-->

        @yield('css')
    </head>
    <!-- END: Head-->

    <body class="vertical-layout page-header-light vertical-menu-collapsible vertical-dark-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-dark-menu" data-col="2-columns">
        @include('business.layouts.header')

        @include('business.layouts.sidebar')

        <!-- BEGIN: Page Main-->
        <div id="main">
            @yield('body')
        </div>
        <!-- END: Page Main-->
        
        @include('business.layouts.footer')
    </body>
</html>
