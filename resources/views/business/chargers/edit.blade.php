@extends('business.layouts.master')

@section('meta')
	<meta name="charger_id" content="{{ $charger -> id }}">
	<meta name="hidden" content="{{ $charger -> hidden }}">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection

@section('css')
	<link rel="stylesheet" href="{{ asset( 'css/business/chargers.css' ) }}" />
@endsection

@section('js')
	<script src="{{ asset('js/business/chargers.js') }}"></script>
@endsection

@section('body')

	<div class="row">
		<div class="col s12">
			<div class="card">
				<div class="card-content">

					<div class="waves-effect waves-teal btn-flat toggle-charger-visibility bpg-arial">
						@if($charger -> hidden)
							@lang('business.chargers.whitelist.unhide-charger')
						@else 
							@lang('business.chargers.whitelist.hide-charger')
						@endif
					</div>

					@if($charger -> hidden)
						<div class="btn whitelist-button bpg-arial">@lang('business.chargers.whitelist.title')</div>
					@endif
					
					<hr style="margin-top:1em; margin-bottom: 1em">
					
					<table class="striped bpg-arial">
						<tbody>
							<tr>
								<td>
									@lang('business.chargers.charger-code')
								</td>
								<td class="users-view-username">
									{{ $charger -> code }}
								</td>
							</tr>

							<tr>
								<td>
									@lang('business.chargers.lat')
								</td>
								<td class="users-view-username">
									{{ $charger -> lat }}
								</td>
							</tr>

							<tr>
								<td>
									@lang('business.chargers.lng')
								</td>
								<td class="users-view-name">
									{{ $charger -> lng }}
								</td>
							</tr>
							<tr>
								<td>
									@lang('business.chargers.public')
								</td>
								<td class="users-view-email">
									<i class="material-icons dp48" style="{{ $charger -> public ? 'color: green' : 'color: red' }}">
										{{ $charger -> public ? 'check' : 'close' }}
									</i>
								</td>
							</tr>

							<tr>
								<td>
									@lang('business.chargers.status')
								</td>
								<td>
									{{ $charger -> status }}
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
										<input 
											value="{{ isset($charger -> getTranslations('name')[$language]) ? $charger -> getTranslations('name')[$language] : null }}" 
											name="{{ 'names[' . $language . ']' }}" 
											id="{{ 'charger_name_' . $language }}" 
											type="text" 
											class="validate bpg-arial"
										/>
										<label 
											for="{{ 'charger_name_' . $language }}" 
											class="bpg-arial"
										>
										@lang('business.chargers.charger-name') {{ "($language)" }}
										</label>
									</div>
								@endforeach
					      	</div>

					      	<div class="row">
					      		@foreach ($languages as $language)
							        <div class="input-field col s4">
										<input 
											value="{{ isset($charger -> getTranslations('description')[$language]) ? $charger -> getTranslations('description')[$language] : null }}"
											name="{{ 'descriptions[' . $language . ']' }}" 
											id="{{ 'charger_desription_' . $language }}" 
											class="validate bpg-arial"
											type="text" 
										>
										<label for="{{ 'charger_desription_' . $language }}">
											@lang('business.chargers.charger-description') {{ "($language)" }}
										</label>
									</div>
								@endforeach
					      	</div>

					      	<div class="row">
					      		@foreach ($languages as $language)
							        <div class="input-field col s4">
										<input 
											value="{{ isset($charger -> getTranslations('location')[$language]) ? $charger -> getTranslations('location')[$language] : null }}"
											type="text" name="{{ 'locations[' . $language . ']' }}" 
											id="{{ 'charger_location_' . $language }}" 
											class="validate bpg-arial"
											>
										<label for="{{ 'charger_location_' . $language }}" class="bpg-arial">
											@lang('business.chargers.charger-location') {{ "($language)" }}
										</label>
									</div>
								@endforeach
					      	</div>

					      	<div class="row" style="margin-bottom: 1rem;">
					      		<div class="input-field col s12">
					      			<label for="charger_business_services" class="bpg-arial">@lang('business.chargers.business-services')</label>
					      		</div>
				      		</div>

				      		<div class="row">
					      		<div class="input-field col s12">
					      			<select id="charger_business_services" name="charger_business_services" class="select2 browser-default" multiple="multiple">
					      				@foreach ($businessServices as $businessService)
					      					<option class="bpg-arial" value="{{ $businessService -> id }}" @if(in_array($businessService -> id, $chargerBusinessServices)) selected @endif>
					      						{{ $businessService -> getTranslation('title', 'ka') }}
					      					</option>
					      				@endforeach
					      			</select>
					      		</div>
					      	</div>

					      	<div class="row">
					      		<div class="input-field col s12" style="display: flex; justify-content: flex-end;">
					      			<button type="submit" class="btn waves-effect waves-light green bpg-arial">@lang('business.chargers.save')</button>
					      		</div>
					      	</div>
					    </form>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('business.chargers.connector-types.lvl2')
	@include('business.chargers.connector-types.fast')
@endsection
