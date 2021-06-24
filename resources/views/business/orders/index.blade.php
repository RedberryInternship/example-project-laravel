@extends('business.layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/business/transactions.css" />
@endsection

@section('js')
    <script src="/js/business/transactions.js"></script>
@endsection

@section('body')
	<div class="row">
		<div class="col s12">

            <div class="card">
                <div class="card-content">
                    <div class="row">
						<div class="col s12 flex justify-space-between">
                            <h4 class="card-title bpg-arial">{{ $tabTitle }}</h4>
                            
                            <a href="{{ $contractDownloadPath }}" class="btn waves-effect waves-light green bpg-arial">
                                @lang('business.transactions.report')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <div class="row">
						<div class="col s12">
                           <form action="" class="row">
                                <div class="col s6">
                                    <div class="input-field">
                                        <i class="material-icons prefix">search</i>
                                        <input type="text" name="search" id="search" value="{{ request() -> get( 'search' ) }}" />
                                        <label for="search">@lang('business.transactions.search')</label>
                                    </div>
                                    <div class="input-field">
                                        <select name="charger_type" id="charger_type">
                                            @php $chargerType = request() -> get('charger_type'); @endphp
    
                                            <option value="" disabled selected>@lang('business.transactions.charger-type')</option>
                                            <option value="FAST" @if($chargerType === 'FAST') selected @endif >
                                                @lang('business.transactions.fast')
                                            </option>
                                            <option value="LVL2" @if($chargerType === 'LVL2') selected @endif >
                                                @lang('business.transactions.lvl2')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field">
                                        <input type="text" name="start_date" value="{{ request() -> get('start_date') }}" class="datepicker" id="start_date">
                                        <label for="start_date">@lang('business.transactions.date-from')</label>
                                    </div>
                                    <div class="input-field">
                                        <input type="text" value="{{ request() -> get('end_date') }}" name="end_date" class="datepicker" id="end_date">
                                        <label for="end_date">@lang('business.transactions.date-to')</label>
                                    </div>
                                </div>
                                
                                <div class="col s6 flex">
                                    <div class="input-field">
                                        <button type="submit" class="btn teal lighten-2 filter-btn bpg-arial">
                                            <i class="material-icons right">filter_list</i>
                                            @lang('business.transactions.filter')
                                        </button>
                                    </div>
                                    <div class="input-field left-2-em filter-btn">
                                        <a href="{{ route('business-orders.index') }}" class="btn white grey-text">
                                            <i class="material-icons">remove</i>
                                        </a>
                                    </div>
                                </div>
                           </form>
                        </div>
                    </div>
                </div>
            </div>

			<div class="card card-tabs">
				<div class="card-content">	
                    <div class="row mb-2">
                        <div class="col s12">
                            @if ($orders -> count())
                                <table class="bpg-arial">
                                    <thead>
                                        <tr style="font-weight:bold; color:black">
                                            <th>@lang('business.transactions.id')</th>
                                            <th>@lang('business.transactions.charger-code')</th>
                                            <th>@lang('business.transactions.charger')</th>
                                            <th>@lang('business.transactions.payments')</th>
                                            <th>@lang('business.transactions.time')</th>
                                            <th class="center"> - </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order -> id }}</td>
                                                <td>{{ $order -> getCharger() -> code }}</td>
                                                <td>{{ $order -> getCharger() -> location }}</td>
                                                <td>
                                                    @foreach ($order -> payments as $payment)
                                                        {{ $payment -> type . ': ' . $payment -> price }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $order -> created_at }}</td>
                                                <td class="center">
                                                    <i class="material-icons dp48 open-modal-button" data-transaction-id="{{ $order -> id }}">remove_red_eye</i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="mt-4 flex">
                                    <i class="material-icons red color-white round mr-1">priority_high</i>
                                    <p>@lang('business.transactions.charger-not-found')</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 center">
                            @include('business.layouts.pagination', ['paginator' => $orders])
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
@endsection
