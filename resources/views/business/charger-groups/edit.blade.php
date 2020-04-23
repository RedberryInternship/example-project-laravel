@extends('business.master')

@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection

@section('js')
@endsection

@section('body')
    <div class="row">
        <div class="col s12">
            <h4 class="card-title">
                {{ $chargerGroup -> name }}
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>ჩარჯერის ჯგუფის დამტენები</th>
                        <th style="text-align: center; width: 300px;">
                            {{ $chargerGroup -> name . ' ჯგუფიდან ამოშლა' }}
                        </th>
                        <th style="text-align: center; width: 200px;">რედაქტირება</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($chargerGroup -> chargers as $charger)
                        <tr>
                            <td>{{ $charger -> name }}</td>
                            <td style="text-align: center;">
                                <form action="{{ url('/business/charger-transfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="charger-id" value="{{ $charger -> id }}">
                                    <input type="hidden" name="charger-group-id" value="{{ $chargerGroup -> id }}">
                                    <input type="hidden" name="remove" value="true">

                                    <button type="submit" class="btn waves-effect waves-light btn-small red">
                                        <i class="material-icons">cancel</i>
                                    </button>
                                </form>
                            </td>

                            <td style="text-align: center">
                                <a href="{{ url('/business/chargers/' . $charger -> id . '/edit') }}" class="btn waves-effect waves-light btn-small">
                                    <i class="material-icons">edit</i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col s12">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>სხვა დამტენები</th>
                        <th>დამტენის ჯგუფი</th>
                        <th style="text-align: center; width: 300px;">{{ $chargerGroup -> name . '-ში გადმოტანა' }}</th>
                        <th style="text-align: center; width: 200px;">რედაქტირება</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($user -> chargers as $allChargerItem)
                        @if ( ! $allChargerItem -> charger_group || $allChargerItem -> charger_group && $allChargerItem -> charger_group -> id != $chargerGroup -> id)
                            <tr>
                                <td>{{ $allChargerItem -> name }}</td>
                                <td>{{ $allChargerItem -> charger_group ? $allChargerItem -> charger_group -> name : NULL }}</td>
                                <td style="text-align: center;">
                                    <form action="{{ url('/business/charger-transfer') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="charger-id" value="{{ $allChargerItem -> id }}">
                                        <input type="hidden" name="charger-group-id" value="{{ $chargerGroup -> id }}">

                                        <button type="submit" class="btn waves-effect waves-light btn-small green">
                                            <i class="material-icons">backup</i>
                                        </button>
                                    </form>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ url('/business/chargers/' . $allChargerItem -> id . '/edit') }}" class="btn waves-effect waves-light btn-small">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

