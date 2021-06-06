@if ( ! isset($charger) || $charger -> hasChargingConnector('lvl2', $chargerConnectorTypes))
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
                                <th>დაწყების დრო</th>
                                <th>დამთავრების დრო</th>
                                <th>მინიმალური სიმძლავრე(კვტ/სთ)</th>
                                <th>მაქსიმალური სიმძლავრე(კვტ/სთ)</th>
                                <th>ღირებულება</th>
                                <th>&nbsp;</th>
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
                                                <td>
                                                    <a href="{{ route('charging-prices.edit', $chargingPrice -> id) }}">
                                                        <button type="submit" class="btn waves-effect waves-light btn-small primary">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                    </a>
                                                </td>
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
                                            <select name="start_time" required class="browser-default">
                                                @foreach ($dayTimesRange as $time)
                                                    <option> {{ $time }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <select name="end_time" required class="browser-default">
                                                @foreach ($dayTimesRange as $time)
                                                    <option @if($time === '24:00') selected @endif>
                                                        {{ $time }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="min_kwt">0</label>
                                            <input type="number" id="min_kwt" name="min_kwt" step="0.01" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="max_kwt">5</label>
                                            <input type="number" id="max_kwt" name="max_kwt" step="0.01" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <label for="price">1</label>
                                            <input type="number" id="price" name="price" step="0.01" >
                                        </div>
                                    </td>
                                    <td class="right">
                                        <div class="input-field">
                                            <button type="submit" class="btn waves-effect waves-light btn-small green">
                                                <i class="material-icons">check</i>
                                            </button>
                                        </div>
                                    </td>
                                    <td></td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
