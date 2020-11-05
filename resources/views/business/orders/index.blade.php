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
                            <h4 class="card-title">{{ $tabTitle }}</h4>
                            
                            <a href="{{ $contractDownloadPath }}" class="btn waves-effect waves-light green">
                                რეპორტი
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
                                <div class="input-field col s3">
                                    <i class="material-icons prefix">search</i>
                                    <input type="text" name="search" id="search" value="{{ request() -> get( 'search' ) }}" />
                                    <label for="search">Search</label>
                                </div>
                                <div class="input-field col s2">
                                    <select name="charger_type" id="charger_type">
                                        @php $chargerType = request() -> get('charger_type'); @endphp

                                        <option value="" disabled selected>დამტენის ტიპი</option>
                                        <option value="FAST" @if($chargerType === 'FAST') selected @endif >სწრაფი</option>
                                        <option value="LVL2" @if($chargerType === 'LVL2') selected @endif >მე-2 დონის</option>
                                    </select>
                                    <label for="charger_type">
                                        დამტენის ტიპი
                                    </label>
                                </div>
                                <div class="input-field col s2">

                                    <input type="text" name="start_date" value="{{ request() -> get('start_date') }}" class="datepicker" id="start_date">
                                    <label for="start_date">თარიღი (დან)</label>
                                </div>
                                <div class="input-field col s2">
                                    <input type="text" value="{{ request() -> get('end_date') }}" name="end_date" class="datepicker" id="end_date">
                                    <label for="end_date">თარიღი (მდე)</label>
                                </div>
                                <div class="input-field col s2">
                                    <button type="submit" class="btn teal lighten-2 filter-btn">
                                        <i class="material-icons right">filter_list</i>
                                        გაფილტვრა
                                    </button>
                                </div>
                                <div class="input-field col s1 filter-btn">
                                    <a href="{{ route('orders.index') }}" class="btn white grey-text">
                                        <i class="material-icons">remove</i>
                                    </a>
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
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>დამტენის კოდი</th>
                                            <th>დამტენი</th>
                                            <th>გადახდები</th>
                                            <th>დრო</th>
                                            <th class="center"> - </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order -> id }}</td>
                                                <td>{{ $order -> charger_connector_type -> charger -> code }}</td>
                                                <td>{{ $order -> charger_connector_type -> charger -> location }}</td>
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
                                    <p>დამტენები არ მოიძებნა</p>
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
