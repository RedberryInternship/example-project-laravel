<!--Line Chart-->
<div>
    <div class="card-content">
        <h4 class="card-title">
            {{-- ტრანზაქციები რაოდენობა | დახარჯული ელ. ენერგია --}}
        </h4>

        <p class="caption mb-2">
            {{-- Here can be text --}}
        </p>

        <div class="charts-select-filter">
            <select id="transactions-select">
                @for($y = now() -> year; $y >= $firstYear; $y--)
                    <option value="{{ $y }}">{{ $y }} წელი</option>
                @endfor
            </select>
        </div>

        <div class="row">
            <div class="col s12 wrap-transactions-chart">
                <div class="sample-chart-wrapper">
                    <canvas id="transactions-chart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
