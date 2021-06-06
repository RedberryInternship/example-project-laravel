@if ( ! isset($charger) || $charger -> hasChargingConnector('fast', $chargerConnectorTypes))
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <table class="striped bpg-arial">
                        <thead>
                            <tr style="color: black">
                                @if (isset($chargerConnectorTypes))
                                    <th>კონექტორის ტიპი</th>
                                @endif
                                <th>წუთები (დან)</th>
                                <th>წუთები (მდე)</th>
                                <th>ღირებულება</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (isset($chargerConnectorTypes))
                                @foreach ($chargerConnectorTypes as $connectorType)
                                    @foreach ($connectorType -> fast_charging_prices as $chargingPrice)
                                        @if ($chargingPrice)
                                            <tr>
                                                <td>{{ $connectorType -> connector_type -> name }}</td>
                                                <td>{{ $chargingPrice -> start_minutes }}</td>
                                                <td>{{ $chargingPrice -> end_minutes }}</td>
                                                <td>{{ $chargingPrice -> price }}</td>
                                                <td>
                                                    <a href="{{ route('fast-charging-prices.edit', $chargingPrice -> id) }}">
                                                        <button type="submit" class="btn waves-effect waves-light btn-small primary">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                    </a>
                                                </td>
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
                            @endif
                            
                            <tr>
                                <form
                                        action="{{ url(isset($group) ? '/business/group-fast-prices/' . $group -> id : '/business/fast-charging-prices') }}"
                                        class="set-fast-charging-price"
                                        
                                        @if(isset($group))
                                        data-group-name="{{ $group -> name }}"
                                        @endif
                                        
                                        method="POST">
                                    @csrf

                                    @if (isset($group))
                                        <input type="hidden" name="_method" value="PUT">
                                    @endif

                                    @if (isset($chargerConnectorTypes))
                                        <td>
                                            <select name="charger_connector_type_id" class="select2 browser-default">
                                                @foreach ($chargerConnectorTypes as $chargerConnectorType)
                                                    <option value="{{ $chargerConnectorType -> id }}">
                                                        {{ $chargerConnectorType -> connector_type -> name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endif
                                    <td>
                                        <div class="input-field">
                                            <label for="start_minutes">0</label>
                                            <input type="number" id="start_minutes" name="start_minutes">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="end_minutes">20</label>
                                            <input type="number" id="end_minutes" name="end_minutes">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="price">1</label>
                                            <input type="number" step="0.01" id="price" name="price">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <button type="submit" class="btn waves-effect waves-light btn-small green">
                                                <i class="material-icons">check</i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="right"></td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
