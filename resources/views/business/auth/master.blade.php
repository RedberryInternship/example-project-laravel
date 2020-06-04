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

        <title>Espace {{ isset($tabTitle) ? ' | ' . $tabTitle : NULL }}</title>

        <link rel="apple-touch-icon" href="/app-assets/images/favicon/apple-touch-icon-152x152.png">
        <link rel="shortcut icon" type="image/x-icon" href="/app-assets/images/favicon/favicon-32x32.png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- BEGIN: VENDOR CSS-->
        <link rel="stylesheet" type="text/css" href="/app-assets/vendors/vendors.min.css">
        <!-- END: VENDOR CSS-->
        <!-- BEGIN: Page Level CSS-->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/themes/horizontal-menu-template/materialize.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/themes/horizontal-menu-template/style.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/layouts/style-horizontal.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/login.css">
        <!-- END: Page Level CSS-->
        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/custom/style.css">
        <!-- END: Custom CSS-->

        @yield('css')
    </head>
    <!-- END: Head-->

    <body class="horizontal-layout page-header-light horizontal-menu preload-transitions 1-column {{ isset($backgroundClassName) ? ' | ' . $backgroundClassName : NULL }}-bg   blank-page blank-page" data-open="click" data-menu="horizontal-menu" data-col="1-column">
        <div class="row">
            <div class="col s12">
                <div class="container">
                    @yield('body')
                </div>

                <div class="content-overlay"></div>
            </div>
        </div>

        <script src="../../../app-assets/js/vendors.min.js"></script>
        <script src="../../../app-assets/js/plugins.js"></script>
    </body>
</html>
