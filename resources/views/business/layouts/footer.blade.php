<footer class="page-footer footer footer-static footer-light navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container">
            <span>&copy; 2020 <a href="https://e-space.ge/" target="_blank">E-Space</a> All rights reserved.</span>
            <span class="right hide-on-small-only">Design and Developed by <a href="https://redberry.ge/">Redberry</a></span>
        </div>
    </div>
</footer>
<script>
    /**
     * Set language functionality for js.
     */
    window.lang = @json(trans('business'));
    window.locale = '{{ app()->getLocale() }}';
    window.__ = function __(key) {
        const keys = key.split('.');
        try {
            return keys.reduce(function (reducedValue, currentValue) {
                return reducedValue[currentValue];
            }, lang);
        } catch (e) {
            return key;
        }
    };
</script>
<script src="/app-assets/js/vendors.min.js"></script>
<script src="/app-assets/vendors/select2/select2.full.min.js"></script>
<script src="/app-assets/vendors/chartjs/chart.min.js"></script>
<script src="/app-assets/js/plugins.js"></script>
<script src="/app-assets/js/search.js"></script>
<script src="/app-assets/js/custom/custom-script.js"></script>
@yield('js')
