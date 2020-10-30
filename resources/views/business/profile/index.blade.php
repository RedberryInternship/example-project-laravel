@extends('business.master')

@section('js')
    <script src="/js/business/profile.js"></script>
@endsection

@section('body')<div class="row">
		<div class="col s12">
            <div class="card">
                <div class="card-content row">
                    <table class="col s5">
                        <tbody>
                            <tr>
                                <td><b>კომპანია</b></td>
                                <td>{{ $company -> name }}</td>
                            </tr>
                            <tr>
                                <td><b>მისამართი</td>
                                <td>{{ $company -> address }}</td>
                            </tr>
                            <tr>
                                <td><b>კონტრაქტის მეთოდი</b></td>
                                <td>{{ $company -> contract_method }}</td>
                            </tr>
                            <tr>
                                <td><b>გადასახადი</b></td>
                                <td>{{ $company -> contract_value }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col s5 push-s1">
                        <tbody>
                            <tr>
                                <td><b>ბანკის ექაუნთი</b></td>
                                <td>{{ $company -> bank_account }}</td>
                            </tr>
                            <tr>
                                <td><b>საიდენტიფიკაციო კოდი</b></td>
                                <td>{{ $company -> identification_code }}</td>
                            </tr>
                            <tr>
                                <td><b>კონტრაქტის დასაწყისი</b></td>
                                <td>{{ $company -> contract_started -> toDateString() }}</td>
                            </tr>
                            <tr>
                                <td><b>კონტრაქტის დასასრული</b></td>
                                <td>{{ $company -> contract_ended -> toDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @if($company -> contract_file)
                    <div class="card-content">
                        <a href="/business/profile/download-contract" class="btn">
                            ჩამოტვირთე კონტრაქტის ფაილი
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
                                    <input id="first_name" type="text" name="first_name" class="validate"
                                        value="{{ $user -> first_name }}" required>
                                    <label for="first_name">სახელი</label>
                                </div>

                                <div class="input-field col s4">
                                    <input id="email" type="email" name="email" class="validate"
                                        value="{{ $user -> email }}" required>
                                    <label for="email">ელ. ფოსტა</label>
                                </div>

                                <div class="input-field col s4">
                                    <input id="phone_number" type="text" name="phone_number" class="validate"
                                        value="{{ $user -> phone_number }}" required>
                                    <label for="phone_number">ტელეფონის ნომერი</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s4">
                                    <input id="password" type="password" name="password" class="validate"
                                        value="">
                                    <label for="password">პაროლი</label>
                                </div>

                                <div class="input-field col s4">
                                    <input id="password_confirmation" type="password" name="password_confirmation" class="validate"
                                        value="">
                                    <label for="password_confirmation">გაიმეორეთ პაროლი</label>
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
@endsection
