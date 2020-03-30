@extends('business.master')

@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection

@section('js')
@endsection

@section('body')
	<div class="row">
		<div class="col s12">
			<div class="card card-tabs">
				<div class="card-content">
					<div class="row">
						<div class="col s12 m6 l10">
	                        <h4 class="card-title">Chargers</h4>
	                    </div>
	                    <div id="view-borderless-table" class="active">
	                        <div class="row">
	                            <div class="col s12">
									<table class="responsive-table">
										<tr>
											<th>ID</th>
											<th>Name</th>
											<th>Charger ID</th>
											<th>Charger Group</th>
											<th>Code</th>
											<th>Public</th>
											<th>Active</th>
											<th>Edit</th>
										</tr>
										@foreach($chargers as $charger)
											<tr>
												<td>{{ $charger -> id }}</td>
												<td>{{ $charger -> name }}</td>
												<td>{{ $charger -> charger_id }}</td>
												<td>{{ $charger -> charger_group ? $charger -> charger_group -> name : null }}</td>
												<td>{{ $charger -> code }}</td>
												<td>
													<i class="material-icons dp48" style="{{ $charger -> public ? 'color: green' : 'color: red' }}">
														{{ $charger -> public ? 'check' : 'close' }}
													</i>
												</td>
												<td>
													<i class="material-icons dp48" style="{{ $charger -> active ? 'color: green' : 'color: red' }}">
														{{ $charger -> active ? 'check' : 'close' }}
													</i>
												</td>
												<td>
													<a href="/business/chargers/{{ $charger -> id }}/edit" class="edit-link">
														<i class="material-icons">edit</i>
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
	</div>
@endsection
