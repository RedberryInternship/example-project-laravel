@extends('business.master')

@section('body')<div class="row">
		<div class="col s12">
			<div class="card card-tabs">
				<div class="card-content">
					<div class="row">
					    <form class="col s12" action="{{ url('/business/profile') }}" method="POST">
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
