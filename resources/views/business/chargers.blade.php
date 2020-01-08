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
										<th>Edit</th>
										<th>Delete</th>
									</tr>
									@foreach($chargers as $charger)
										<tr>
											<td>{{ $charger -> id }}</td>
											<td>{{ $charger -> name }}</td>
											<td>{{ $charger -> charger_id }}</td>
											<td>{{ $charger -> code }}</td>
											<td>{{ $charger -> public }}</td>
											<td>{{ $charger -> active }}</td>
											<td><a href="/business/charger-edit/{{ $charger -> id }}" class="edit-link"><i class="material-icons">edit</i></a></td>
											<td><a href="/business/charger-delete" class="delete-link"><i class="material-icons">delete_forever</i></a></td>
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