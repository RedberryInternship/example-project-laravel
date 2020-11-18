@extends('business.layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/business/groups.css" />
@endsection

@section('js')
    <script src="/js/business/groups-edit.js"></script>
@endsection

@section('meta')
    <meta name="group_id" content="{{ $group -> id }}" />
@endsection

@section('body')
    <div class="row flex align-center">
        <div class="col s2">
            <h4 class="card-title bpg-arial">
                {{ $group -> name }}
            </h4>
        </div>

        <div class="col s10 flex align-center justify-flex-end buttons-wrapper">
            <a class="delete-group-prices-btn">
                <button type="submit" class="btn red darken-4 waves-effect waves-light col pull-s3 bpg-arial">
                    ჯგუფის ტარიფების წაშლა
                </button>
            </a>
            
            <a href="{{ url('/business/group-prices/' . $group -> id) }}">
                <button type="submit" class="btn waves-effect waves-light btn-small col pull-s1 bpg-arial">
                    level2 დამტენების ფასების დამატება
                </button>
            </a>

            <a href="{{ url('/business/group-fast-prices/' . $group -> id) }}">
                <button type="submit" class="btn waves-effect waves-light btn-small bpg-arial">
                    სწრაფი დამტენების ფასების დამატება
                </button>
            </a>
        </div>
    </div>

    @if ($group -> chargers -> count())
        <div class="row">
            <div class="col s12 blue-grey">
                <table class="responsive-table white-text bpg-arial">
                    <thead>
                        <tr>
                            <th>ჩარჯერის ჯგუფის დამტენები</th>
                            
                            <th style="text-align: center; width: 200px;">რედაქტირება</th>
                            <th style="text-align: center; width: 300px;">
                                {{ $group -> name . ' ჯგუფიდან ამოშლა' }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($group -> chargers as $charger)
                            <tr>
                                <td>{{ $charger -> name }}</td>
                                <td style="text-align: center">
                                    <a href="{{ url('/business/chargers/' . $charger -> id . '/edit') }}" class="btn waves-effect waves-light btn-small">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(! $allChargersAreIn)
        <br>
        <div class="row">
            <div class="col s12">
                <div class="btn right orange darken-4 select-all-button bpg-arial">
                    <i class="material-icons right">keyboard_capslock</i>
                    ყველა დამტენის ჯგუფში დამატება
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col s12 teal lighten-5">
                <table class="responsive-table bpg-arial">
                    <thead>
                        <tr>
                            <th>
                                სხვა დამტენები
                            </th>
                            <th style="text-align: center; width: 200px;">რედაქტირება</th>
                            <th style="text-align: center; width: 300px;">{{ $group -> name . '-ში დამატება' }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user -> company -> chargers as $charger)
                            @if ( ! in_array($charger -> id, $groupChargerIds))
                                <tr>
                                    <td>{{ $charger -> name }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/business/chargers/' . $charger -> id . '/edit') }}" class="btn waves-effect waves-light btn-small">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </td>
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
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection

