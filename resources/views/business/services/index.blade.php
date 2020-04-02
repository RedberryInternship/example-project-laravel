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
						<div class="col s6">
	                        <h4 class="card-title">Business Services</h4>
	                    </div>

	                    <div class="col s6">
	                    	<p>
	                        	<a href="{{ url('/business/services/create') }}" class="waves-effect waves-light btn right">სერვისის დამატება</a>
	                        </p>
	                    </div>
	                </div>

	                @if ($user -> business_services -> count())
	                    <div class="row">
		                    <div id="view-borderless-table" class="active" style="margin-top: 3rem;">
		                        <div class="row">
		                            <div class="col s12">
										<table class="responsive-table">
											<thead>
												<tr>
													<th>ID</th>
													@foreach ($languages as $language)
														<th>{{ 'სათაური (' . $language . ')' }}</th>
													@endforeach
													@foreach ($languages as $language)
														<th>{{ 'აღწერა (' . $language . ')' }}</th>
													@endforeach
													<th style="text-align: center;">რედაქტირება</th>
													<th style="text-align: center;">წაშლა</th>
												</tr>
											</thead>
											
											<tbody>
												@foreach($user -> business_services as $service)
													<tr>
														<td>{{ $service -> id }}</td>
														@foreach ($languages as $language)
															<td>{{ $service -> getTranslation('title', $language) }}</td>
														@endforeach
														@foreach ($languages as $language)
															<td>{{ $service -> getTranslation('description', $language) }}</td>
														@endforeach
														<td style="text-align: center;">
															<a href="{{ url('/business/services/' . $service -> id . '/edit') }}" class="btn waves-effect waves-light btn-small">
																<i class="material-icons">edit</i>
															</a>
														</td>
														<td style="text-align: center;">
															<form action="{{ url('/business/services/' . $service -> id) }}" method="POST">
																@csrf
																<input type="hidden" name="_method" value="delete">

																<button type="submit" class="btn waves-effect waves-light btn-small red">
																	<i class="material-icons">delete_forever</i>
																</button>
															</form>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
