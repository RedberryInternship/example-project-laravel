<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <table class="striped">
                    <thead>
                        <tr>
                            <th>კონექტორის ტიპი</th>
                            <th>წუთები (დან)</th>
                            <th>წუთები (მდე)</th>
                            <th>საფასური</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($chargerConnectorTypes as $connectorType)
                            @if ($connectorType -> has('fast_charging_prices'))
                                @foreach ($connectorType -> fast_charging_prices as $chargingPrice)
                                    @if ($chargingPrice)
                                        <tr>
                                            <td>{{ $connectorType -> connector_type -> name }}</td>
                                            <td>{{ $fastChargingPrice -> start_minutes }}</td>
                                            <td>{{ $fastChargingPrice -> end_minutes }}</td>
                                            <td>{{ $fastChargingPrice -> price }}</td>
                                            <td style="text-align: right;">
                                                <a href="{{ url('/business/fast-charging-prices/' . $fastChargingPrice -> id . '/destroy') }}" class="btn waves-effect waves-light red accent-2">
                                                    წაშლა
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
