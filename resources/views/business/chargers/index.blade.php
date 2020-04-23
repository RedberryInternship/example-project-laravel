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
	                        <h4 class="card-title">დამტენები</h4>
	                    </div>
	                    <div id="view-borderless-table" class="active">
	                        <div class="row">
	                            <div class="col s12">
									<table class="responsive-table">
										<thead>
											<tr>
												<th>ID</th>
												<th>სახელი</th>
												<th>კოდი</th>
												<th>დამტენის ჯგუფი</th>
												<th style="text-align: center;">საჯარო</th>
												<th style="text-align: center;">აქტიური</th>
												<th style="text-align: center;">რედაქტირება</th>
											</tr>
										</thead>

										<tbody>
											@foreach($chargers as $charger)
												<tr>
													<td>{{ $charger -> id }}</td>
													<td>{{ $charger -> name }}</td>
													<td>{{ $charger -> code }}</td>
													<td>{{ $charger -> charger_group ? $charger -> charger_group -> name : null }}</td>
													<td style="text-align: center;">
														<i class="material-icons dp48" style="{{ $charger -> public ? 'color: green' : 'color: red' }}">
															{{ $charger -> public ? 'check' : 'close' }}
														</i>
													</td>
													<td style="text-align: center;">
														<i class="material-icons dp48" style="{{ $charger -> active ? 'color: green' : 'color: red' }}">
															{{ $charger -> active ? 'check' : 'close' }}
														</i>
													</td>
													<td style="text-align: center;">
														<a href="/business/chargers/{{ $charger -> id }}/edit" class="btn waves-effect waves-light btn-small">
															<i class="material-icons">edit</i>
														</a>
													</td>
												</tr>
											@endforeach
										</tbody>
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
