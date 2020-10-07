@extends('business.master')

@section('body')
	<div class="row">
		<div class="col s12">
			<div class="card card-tabs">
				<div class="card-content">
					<div class="row mb-2">
						<div class="col s12 flex justify-space-between">
                            <h4 class="card-title">{{ $tabTitle }}</h4>
                            
                            <a href="{{ url('/business/order-exports') }}" class="btn waves-effect waves-light green">
                                რეპორტი
                            </a>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col s12">
                            @if ($orders -> count())
                                <table class="responsive-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>დამტენის კოდი</th>
                                            <th>დამტენი</th>
                                            <th>გადახდები</th>
                                            <th>დრო</th>
                                            <th class="center">დასრულებული</th>
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
                                                    <i class="material-icons dp48" style="{{ $order -> charging_status == 'FINISHED' ? 'color: green' : 'color: red' }}">
                                                        {{ $order -> charging_status == 'FINISHED' ? 'check' : 'close' }}
                                                    </i>
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
