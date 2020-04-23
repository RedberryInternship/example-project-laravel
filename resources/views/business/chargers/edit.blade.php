@extends('business.master')

@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection

@section('body')
	<div class="row">
		<div class="col s12">
			<div class="card">
				<div class="card-content">
					<table class="striped">
						<tbody>
							<tr>
								<td>
									კოდი
								</td>
								<td class="users-view-username">
									{{ $charger -> code }}
								</td>
							</tr>

							<tr>
								<td>
									დამტენის ჯგუფი
								</td>
								<td class="users-view-username" style="width: 600px;">
									@if ($charger -> charger_group)
										{{ $charger -> charger_group -> name }}

					      			    <a href="{{ url('/business/charger-groups/' . $charger -> charger_group -> id . '/edit') }}" class="btn waves-effect waves-light btn-small" style="margin-left: 4rem;">
                                            რედაქტირება
                                        </a> 
									@else
										<i class="material-icons dp48">remove</i>
									@endif
								</td>
							</tr>

							<tr>
								<td>
									განედი (lat)
								</td>
								<td class="users-view-username">
									{{ $charger -> lat }}
								</td>
							</tr>

							<tr>
								<td>
									გრძედი (lng)
								</td>
								<td class="users-view-name">
									{{ $charger -> lng }}
								</td>
							</tr>

							<tr>
								<td>
									საჯარო
								</td>
								<td class="users-view-email">
									<i class="material-icons dp48" style="{{ $charger -> public ? 'color: green' : 'color: red' }}">
										{{ $charger -> public ? 'check' : 'close' }}
									</i>
								</td>
							</tr>

							<tr>
								<td>
									აქტიური
								</td>
								<td class="users-view-email">
									<i class="material-icons dp48" style="{{ $charger -> active ? 'color: green' : 'color: red' }}">
										{{ $charger -> active ? 'check' : 'close' }}
									</i>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col s12">
			<div class="card card-tabs">
				<div class="card-content">
					<div class="row">
					    <form class="col s12" action="{{ url('/business/chargers/' . $charger -> id) }}" method="POST">
					    	@csrf
					    	<input type="hidden" name="_method" value="PUT">

					      	<div class="row">
					      		@foreach ($languages as $language)
							        <div class="input-field col s4">
										<input id="{{ 'charger_name_' . $language }}" type="text" name="{{ 'names[' . $language . ']' }}" class="validate"
											value="{{ isset($charger -> getTranslations('name')[$language]) ? $charger -> getTranslations('name')[$language] : null }}">
										<label for="{{ 'charger_name_' . $language }}">{{ 'დანტენის სახელი (' . $language . ')' }}</label>
									</div>
								@endforeach
					      	</div>

					      	<div class="row">
					      		@foreach ($languages as $language)
							        <div class="input-field col s4">
										<input id="{{ 'charger_desription_' . $language }}" type="text" name="{{ 'descriptions[' . $language . ']' }}" class="validate"
											value="{{ isset($charger -> getTranslations('description')[$language]) ? $charger -> getTranslations('description')[$language] : null }}">
										<label for="{{ 'charger_desription_' . $language }}">{{ 'დანტენის აღწერა (' . $language . ')' }}</label>
									</div>
								@endforeach
					      	</div>

					      	<div class="row">
					      		@foreach ($languages as $language)
							        <div class="input-field col s4">
										<input id="{{ 'charger_location_' . $language }}" type="text" name="{{ 'locations[' . $language . ']' }}" class="validate"
											value="{{ isset($charger -> getTranslations('location')[$language]) ? $charger -> getTranslations('location')[$language] : null }}">
										<label for="{{ 'charger_location_' . $language }}">{{ 'დამტენის მდებარეობა (' . $language . ')' }}</label>
									</div>
								@endforeach
					      	</div>

					      	<div class="row" style="margin-bottom: 1rem;">
					      		<div class="input-field col s12">
					      			<label for="charger_business_services">ბიზნეს სერვისები</label>
					      		</div>
				      		</div>

				      		<div class="row">
					      		<div class="input-field col s12">
					      			<select id="charger_business_services" name="charger_business_services[]" class="select2 browser-default" multiple="multiple">
					      				@foreach ($businessServices as $businessService)
					      					<option value="{{ $businessService -> id }}" @if(in_array($businessService -> id, $chargerBusinessServices)) selected @endif>
					      						{{ $businessService -> getTranslation('title', 'ka') }}
					      					</option>
					      				@endforeach
					      			</select>
					      		</div>
					      	</div>

					      	<div class="row">
					      		<div class="input-field col s12" style="display: flex; justify-content: flex-end;">
					      			<button type="submit" class="btn waves-effect waves-light green">დამახსოვრება</button>
					      		</div>
					      	</div>
					    </form>
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('business.connector-types.lvl2')

	@include('business.connector-types.fast')
@endsection
