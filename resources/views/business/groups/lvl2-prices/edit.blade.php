@extends('business.layouts.master')

@section('css')
<link rel="stylesheet" href="/css/business/groups.css" />
@endsection

@section('js')
    <script src="/js/business/groups-set-lvl2-prices.js"></script>    
@endsection

@section('body')
    <div class="row flex align-center">
        <div class="col s12 flex align-center">
            <h4 class="card-title">
                {{ $group -> name }}
            </h4>

            <h5>&nbsp; - @lang('business.groups.lvl2-prices')</h5>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            @include('business.chargers.connector-types.lvl2', ['group' => $group])
        </div>
    </div>
@endsection
