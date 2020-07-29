@extends('business.master')

@section('body')
    <div class="row">
        <div class="col s12">
            <h4 class="card-title">
                {{ $group -> name }}
            </h4>
        </div>
    </div>

    @if ($group -> chargers -> count())
        <div class="row">
            <div class="col s12">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>ჩარჯერის ჯგუფის დამტენები</th>
                            <th style="text-align: center; width: 300px;">
                                {{ $group -> name . ' ჯგუფიდან ამოშლა' }}
                            </th>
                            <th style="text-align: center; width: 200px;">რედაქტირება</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($group -> chargers as $charger)
                            <tr>
                                <td>{{ $charger -> name }}</td>
                                <td style="text-align: center;">
                                    <form action="{{ url('/business/charger-transfer') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="charger-id" value="{{ $charger -> id }}">
                                        <input type="hidden" name="group-id" value="{{ $group -> id }}">
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
    @endif

    <br>

    <div class="row">
        <div class="col s12">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>სხვა დამტენები</th>
                        <th style="text-align: center; width: 300px;">{{ $group -> name . '-ში დამატება' }}</th>
                        <th style="text-align: center; width: 200px;">რედაქტირება</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($user -> company -> chargers as $charger)
                        @if ( ! in_array($charger -> id, $groupChargerIds))
                            <tr>
                                <td>{{ $charger -> name }}</td>
                                <td style="text-align: center;">
                                    <form action="{{ url('/business/charger-transfer') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="charger-id" value="{{ $charger -> id }}">
                                        <input type="hidden" name="group-id" value="{{ $group -> id }}">

                                        <button type="submit" class="btn waves-effect waves-light btn-small green">
                                            <i class="material-icons">backup</i>
                                        </button>
                                    </form>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ url('/business/chargers/' . $charger -> id . '/edit') }}" class="btn waves-effect waves-light btn-small">
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

