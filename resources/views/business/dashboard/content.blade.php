<div id="dashboard-content" class="row dashboard-content">
    <div class="col s12">
        <div class="card gradient-shadow border-radius-3" style="height: 500px;">
            <div class="content-pages">
                <div class="content-page active">
                    @include('business.charts.transactions')
                </div>

                <div class="content-page">
                    @include('business.charts.income')
                </div>

                <div class="content-page">
                    @include('business.charts.energy')
                </div>

                <div class="content-page">
                    4
                </div>
            </div>
        </div>
    </div>
</div>
