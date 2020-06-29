@if ($charger -> hasChargingConnector('fast', $chargerConnectorTypes))
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
                                <th>ღირებულება</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($chargerConnectorTypes as $connectorType)
                                @foreach ($connectorType -> fast_charging_prices as $chargingPrice)
                                    @if ($chargingPrice)
                                        <tr>
                                            <td>{{ $connectorType -> connector_type -> name }}</td>
                                            <td>{{ $chargingPrice -> start_minutes }}</td>
                                            <td>{{ $chargingPrice -> end_minutes }}</td>
                                            <td>{{ $chargingPrice -> price }}</td>
                                            <td class="right">
                                                
                                                <form action="{{ url('/business/fast-charging-prices/' . $chargingPrice -> id) }}" method="POST">
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
                            @endforeach
                            
                            <tr>
                                <form action="{{ url('/business/fast-charging-prices') }}" method="Post">
                                    @csrf

                                    <td> 
                                        <select name="charger_connector_type_id" class="select2 browser-default">
                                            @foreach ($chargerConnectorTypes as $chargerConnectorType)
                                                <option value="{{ $chargerConnectorType -> id }}">
                                                    {{ $chargerConnectorType -> connector_type -> name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="start_minutes">0</label>
                                            <input type="text" id="start_minutes" name="start_minutes">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="end_minutes">20</label>
                                            <input type="text" id="end_minutes" name="end_minutes">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="price">1</label>
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
@endif
