const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix .js('resources/js/business/dashboard/dashboard.js',     'public/js/business')
    .js('resources/js/business/chargers/chargers.js',       'public/js/business')
    .sass('resources/sass/business/dashboard/index.scss',   'public/css/business/dashboard.css')
    .sass('resources/sass/business/login.scss',             'public/css/business')
    .sass('resources/sass/business/sidebar.scss',           'public/css/business')
    .sass('resources/sass/nova/main.scss',                  'public/css/nova');
