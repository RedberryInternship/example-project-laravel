@extends('business.layouts.master')

@section('js')
    <script src="/js/business/profile.js"></script>
@endsection

@section('body')<div class="row">
		<div class="col s12">
            <div class="card">
                <div class="card-content row">
                    <table class="col s5 bpg-arial">
                        <tbody>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.company')</b></td>
                                <td>{{ $company -> name ?? '---' }}</td>
                            </tr>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.address')</td>
                                <td>{{ $company -> address ?? '---' }}</td>
                            </tr>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.contract-method')</b></td>
                                <td>{{ $company -> contract_method ?? '---' }}</td>
                            </tr>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.contract-value')</b></td>
                                <td>{{ $company -> contract_value ?? '---' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col s5 push-s1 bpg-arial">
                        <tbody>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.bank-account')</b></td>
                                <td>{{ $company -> bank_account ?? '---' }}</td>
                            </tr>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.identification-code')</b></td>
                                <td>{{ $company -> identification_code ?? '---' }}</td>
                            </tr>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.contract-started')</b></td>
                                <td>{{ $company -> contract_started ? $company -> contract_started -> toDateString() : '---' }}</td>
                            </tr>
                            <tr>
                                <td class="black-text"><b>@lang('business.profile.contract-ended')</b></td>
                                <td>{{ $company -> contract_ended ? $company -> contract_ended -> toDateString() : '---' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @if($company -> contract_file)
                    <div class="card-content">
                        <a href="/business/profile/download-contract" class="btn bpg-arial">
                            @lang('business.profile.download-contract')
                            <i class="material-icons right">attachment</i>
                        </a>
                    </div>
                @endif
            </div>

			<div class="card card-tabs">
				<div class="card-content">
					<div class="row">
					    <form class="col s12 save-profile" action="{{ url('/business/profile') }}" method="POST">
					    	@csrf

					      	<div class="row">
                                <div class="input-field col s4">
                                    <input id="first_name" type="text" name="first_name" class="validate bpg-arial"
                                        value="{{ $user -> first_name }}" required>
                                    <label for="first_name" class="bpg-arial">@lang('business.profile.name')</label>
                                </div>

                                <div class="input-field col s4">
                                    <input id="email" type="email" name="email" class="validate bpg-arial"
                                        value="{{ $user -> email }}" required>
                                    <label for="email" class="bpg-arial">@lang('business.profile.email')</label>
                                </div>

                                <div class="input-field col s4">
                                    <input id="phone_number" type="text" name="phone_number" class="validate bpg-arial"
                                        value="{{ $user -> phone_number }}" required>
                                    <label for="phone_number" class="bpg-arial">@lang('business.profile.phone')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s4">
                                    <input id="password" type="password" name="password" class="validate bpg-arial"
                                        value="">
                                    <label for="password" class="bpg-arial">@lang('business.profile.password')</label>
                                </div>

                                <div class="input-field col s4">
                                    <input id="password_confirmation" type="password" name="password_confirmation" class="validate bpg-arial"
                                        value="">
                                    <label for="password_confirmation" class="bpg-arial">@lang('business.profile.repeat-password')</label>
                                </div>
                            </div>

					      	<div class="row">
					      		<div class="input-field col s12" style="display: flex; justify-content: flex-end;">
					      			<button type="submit" class="btn waves-effect waves-light green">@lang('business.profile.save')</button>
					      		</div>
					      	</div>
					    </form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
