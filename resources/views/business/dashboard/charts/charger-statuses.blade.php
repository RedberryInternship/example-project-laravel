<!--Line Chart-->
<div>
    <div class="card-content">
        <h4 class="card-title">
            @lang('business.dashboard.charger-statuses.sub-title')
        </h4>

        <p class="caption mb-2">
        {{-- Description --}}
        </p>

        <div class="row">
            <div class="col s6">
                <div class="sample-chart-wrapper">
                    <p class="caption center">
                        <strong>@lang('business.dashboard.charger-statuses.lvl-2')</strong>
                    </p>

                    <canvas id="charger-statuses-chart-lvl2" width="100%" height="400"></canvas>
                </div>
            </div>

            <div class="col s6">
                <div class="sample-chart-wrapper">
                    <p class="caption center">
                        <strong>@lang('business.dashboard.charger-statuses.fast')</strong>
                    </p>

                    <canvas id="charger-statuses-chart-fast" width="100%" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
