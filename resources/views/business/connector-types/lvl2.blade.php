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
                                                <form action="{{ url('/business/charging-prices/' . $chargingPrice -> id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="delete">

                                                    <button type="submit" class="btn waves-effect waves-light btn-small red">
                                                        <i class="material-icons">cancel</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach


                        <tr>
                            <form action="{{ url('/business/charging-prices') }}" method="POST">
                                @csrf

                                <td>
                                    <select id="charger_connector_type_id" name="charger_connector_type_id" class="select2 browser-default">
                                        @foreach ($chargerConnectorTypes as $chargerConnectorType)
                                            <option value="{{ $chargerConnectorType -> id }}">
                                                {{ $chargerConnectorType -> connector_type -> name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <label for="start_time">დაწყების დრო</label>
                                        <input type="text" id="start_time" name="start_time">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <label for="end_time">დამთავრების დრო</label>
                                        <input type="text" id="end_time" name="end_time">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <label for="min_kwt">მინიმალური კილოვატი</label>
                                        <input type="text" id="min_kwt" name="min_kwt">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <label for="max_kwt">დამთავრების დრო</label>
                                        <input type="text" id="max_kwt" name="max_kwt">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <label for="price">ღირებულება</label>
                                        <input type="text" id="price" name="price">
                                    </div>
                                </td>
                                <td class="right">
                                    <div class="input-field">
                                        <button type="submit" class="btn waves-effect waves-light btn-small green">
                                            <i class="material-icons">check</i>
                                        </button>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

