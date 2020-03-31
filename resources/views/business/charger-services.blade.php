@extends('business.master')
@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection
@section('body')
<div class="row">
	<div class="col s12">
		<div class="card card-tabs">
			<div class="card-content">
				<div class="row">
					<div class="col s12 m6 l10">
                        <h4 class="card-title">Charger Services</h4>
                        <p>Charger title: {{ $charger -> name }}</p>
                    </div>
				</div>
				<div class="row">
						<div class="col s12 m6 l10">
						<div class="col s7">		
							<form action="/business/add-charger-bussiness-service" method="POST">
								<div class="col s9">
									@csrf
									<input type="hidden" name="charger_id" value="{{ $charger -> id }}">
									<select name="business_service_id">
										@foreach($business_services as $service)
											<option value="{{ $service -> id}}">{{ $service -> title_ka }}</option>
										@endforeach
									</select>
								</div>
								<div class="col s3">
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit
		                                <i class="material-icons right">send</i>
		                            </button>
								</div>
							</form>
						</div>
						<div class="col s5">
							<table class="responsive-table">
								<tr>
									<th>Service name</th>
									<th>Remove</th>
								</tr>					
								@foreach($charger_business_service as $service)
									<tr>
									<td>{{ $service -> business_service -> title_ka }}</td>
									<td>
										<a href="/business/delete-charger-business-service/{{ $service -> id }}" class="delete_forever">
											<i class="material-icons">delete_forever</i>
										</a>
									</td>
									</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection