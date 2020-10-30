@if ( ! isset($charger) || $charger -> hasChargingConnector('lvl2', $chargerConnectorTypes))
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <table class="striped">
                        <thead>
                            <tr>
                                @if (isset($chargerConnectorTypes))
                                    <th>კონექტორის ტიპი</th>
                                @endif
                                <th>დაწყების დრო</th>
                                <th>დამთავრების დრო</th>
                                <th>მინიმალური კილოვატები</th>
                                <th>მაქსიმალური კილოვატები</th>
                                <th>ღირებულება</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (isset($chargerConnectorTypes))
                                @foreach ($chargerConnectorTypes as $connectorType)
                                    @foreach ($connectorType -> charging_prices as $chargingPrice)
                                        @if ($chargingPrice)
                                            <tr>
                                                <td>{{ $connectorType -> connector_type -> name }}</td>
                                                <td>{{ $chargingPrice -> start_time }}</td>
                                                <td>{{ $chargingPrice -> end_time }}</td>
                                                <td>{{ $chargingPrice -> min_kwt }}</td>
                                                <td>{{ $chargingPrice -> max_kwt }}</td>
                                                <td>{{ $chargingPrice -> price }}</td>
                                                <td class="right">
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
                                @endforeach
                            @endif

                            <tr>
                                <form
                                        action="{{ url(isset($group) ? '/business/group-prices/' . $group -> id : '/business/charging-prices') }}"
                                        class="set-lvl2-charging-price"
                                        
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
                                            <label for="start_time">00:00</label>
                                            <input type="text" id="start_time" name="start_time" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="end_time">24:00</label>
                                            <input type="text" id="end_time" name="end_time" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="min_kwt">0</label>
                                            <input type="text" id="min_kwt" name="min_kwt">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="max_kwt">5</label>
                                            <input type="text" id="max_kwt" name="max_kwt">
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
