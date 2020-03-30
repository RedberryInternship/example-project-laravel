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
                        <h4 class="card-title">Chargers</h4>
                        <p>{{ $user -> first_name }}'s chargers list.</p>
                    </div>
                    <div id="view-borderless-table" class="active">
                        <div class="row">
                            <div class="col s12">
								<table class="responsive-table">
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Charger id</th>
										<th>Code</th>
										<th>Public</th>
										<th>Active</th>
										<th>Services</th>
									</tr>
									@foreach($chargers as $charger)
										<tr>
											<td>{{ $charger -> id }}</td>
											<td>{{ $charger -> name }}</td>
											<td>{{ $charger -> charger_id }}</td>
											<td>{{ $charger -> code }}</td>
											<td>{{ $charger -> public }}</td>
											<td>{{ $charger -> active }}</td>
											<td><a href="/business/charger-services/{{ $charger -> id }}" class="remove_red_eye"><i class="material-icons">remove_red_eye</i></a></td>
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
</div>
@endsection