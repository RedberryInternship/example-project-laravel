@extends('business.master')

@section('body')
    <div class="row flex align-center">
        <div class="col s12 flex align-center">
            <h4 class="card-title">
                {{ $group -> name }}
            </h4>

            <h5>&nbsp; - leve2 დამტენის ფასები</h5>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            @include('business.chargers.connector-types.lvl2', ['group' => $group])
        </div>
    </div>
@endsection
