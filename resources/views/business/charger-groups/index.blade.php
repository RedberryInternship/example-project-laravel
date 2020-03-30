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
	                        <h4 class="card-title">დამტენების ჯგუფები</h4>
	                    </div>
	                    <div id="view-borderless-table" class="active">
	                        <div class="row">
	                            <div class="col s12">
									<table class="responsive-table">
										<tr>
											<th>ID</th>
											<th>სახელი</th>
											<th style="text-align: center;">დამტენების რაოდენობა</th>
											<th style="text-align: center;">რედაქტირება</th>
										</tr>
										@foreach($chargerGroups as $chargerGroup)
											<tr>
												<td>{{ $chargerGroup -> id }}</td>
												<td>{{ $chargerGroup -> name }}</td>
												<td style="text-align: center;">{{ $chargerGroup -> chargers -> count() }}</td>
												<td style="text-align: center;">
													<a href="/business/charger-groups/{{ $chargerGroup -> id }}/edit" class="edit-link">
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
