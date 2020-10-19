<!--Line Chart-->
<div>
    <div class="card-content">
        <h4 class="card-title">
            {{-- შემოსავალი --}}
        </h4>

        <p class="caption mb-2">
            {{-- Description --}}
        </p>

        @php 
            $year = 2019;
            $currentYear = now() -> year;
        @endphp

        <div class="charts-select-filter">
            <select id="income-expense-select">
                @for($y = $currentYear; $y >= $year; $y--)
                    <option value="{{ $y }}">{{ $y }} წელი</option>
                @endfor
            </select>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="sample-chart-wrapper wrap-income-expense-chart">
                    <canvas id="income-expense-chart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
