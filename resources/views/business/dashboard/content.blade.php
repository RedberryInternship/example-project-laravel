@include('business.dashboard.chargers-modal')

<div id="dashboard-content" class="row dashboard-content">
    <div class="col s12">
        <div class="card gradient-shadow border-radius-3" style="height: 600px;">
            <div class="content-pages">
                <div class="content-page active">
                    @include('business.dashboard.charts.transactions')
                </div>

                <div class="content-page">
                    @include('business.dashboard.charts.income-expense')
                </div>

                <div class="content-page">
                    @include('business.dashboard.charts.top-chargers')
                </div>

                <div class="content-page">
                    @include('business.dashboard.charts.charger-statuses')
                </div>
            </div>
        </div>
    </div>
</div>
