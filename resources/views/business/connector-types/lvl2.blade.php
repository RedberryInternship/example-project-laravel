<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <table class="striped">
                    <thead>
                        <tr>
                            <th>კონექტორის ტიპი</th>
                            <th>დრო (დან)</th>
                            <th>დრო (მდე)</th>
                            <th>kwt (დან)</th>
                            <th>kwt (მდე)</th>
                            <th>საფასური</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($chargerConnectorTypes as $connectorType)
                            @if ($connectorType -> has('charging_prices'))
                                @foreach ($connectorType -> charging_prices as $chargingPrice)
                                    @if ($chargingPrice)
                                        <tr>
                                            <td>{{ $connectorType -> connector_type -> name }}</td>
                                            <td>{{ $chargingPrice -> start_time }}</td>
                                            <td>{{ $chargingPrice -> end_time }}</td>
                                            <td>{{ $chargingPrice -> min_kwt }}</td>
                                            <td>{{ $chargingPrice -> max_kwt }}</td>
                                            <td>{{ $chargingPrice -> price }}</td>
                                            <td style="text-align: right;">
                                                <a href="{{ url('/business/charging-prices/' . $chargingPrice -> id . '/destroy') }}" class="btn waves-effect waves-light red accent-2">
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
