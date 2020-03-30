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
                        <h4 class="card-title">Business Services</h4>
                        <p>{{ $user -> first_name }}'s Services list.</p>
                    </div>
                    <div class="col s12 m6 l10">
                    	<p>
                        	<a href="add-business-service" class="waves-effect waves-light  btn">სერვისის დამატება</a>
                        </p>
                    </div>
                    <div id="view-borderless-table" class="active">
                        <div class="row">
                            <div class="col s12">
								<table class="responsive-table">
									<tr>
										<th>ID</th>
										<th>Title_ge</th>
										<th>Title_ru</th>
										<th>Title_en</th>
										<th>Description_ge</th>
										<th>Description_ru</th>
										<th>Description_en</th>
										<th>Img</th>
										<th>Delete</th>
									</tr>
									@foreach($business_services as $service)
										<tr>
											<td>{{ $service -> id }}</td>
											<td>{{ $service -> title_ka }}</td>
											<td>{{ $service -> title_ru }}</td>
											<td>{{ $service -> title_en }}</td>
											<td>{{ $service -> description_ka }}</td>
											<td>{{ $service -> description_ru }}</td>
											<td>{{ $service -> description_en }}</td>
											<td>
												<img src="/images/business-services/{{ $user -> id }}/{{ $service -> id }}/{{ $service -> image }}" style="max-height: 30px">
											</td>
											<td>
												<a href="/business/delete-business-service/{{ $service -> id }}" class="delete_forever">
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
</div>
@endsection