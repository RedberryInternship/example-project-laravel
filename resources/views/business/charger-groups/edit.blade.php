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
                        <th>დამტენი</th>
                        <th style="text-align: center;">რედაქტირება</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($chargerGroup -> chargers as $charger)
                        <tr>
                            <td>{{ $charger -> name }}</td>
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
@endsection

