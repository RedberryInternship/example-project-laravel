@extends('business.master')
@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection
@section('body')
{{ dd($charger -> name) }}
<div class="row">
	<div class="col s12">
		<div class="card card-tabs">
			<div class="card-content">
				<div class="row">
				    <form class="col s12" action="" method="POST">
				      <div class="row">
					        <div class="input-field col s4">
					          <input id="charger_name_ka" type="text" class="validate" value="">
					          <label for="charger_name_ka">Charger Name KA</label>
					      	</div>
					      	<div class="input-field col s4">
					          <input id="charger_name_en" type="text" class="validate">
					          <label for="charger_name_en">Charger Name EN</label>
					        </div>
					      	<div class="input-field col s4">
					          <input id="charger_name_en" type="text" class="validate">
					          <label for="charger_name_en">Charger Name RU</label>
					        </div>
				      </div>
				      <div class="row">
					        <div class="input-field col s4">
					          <input id="description_name_ka" type="text" class="validate">
					          <label for="description_name_ka">Description Name KA</label>
					      	</div>
					      	<div class="input-field col s4">
					          <input id="description_name_en" type="text" class="validate">
					          <label for="description_name_en">Description Name EN</label>
					        </div>
					      	<div class="input-field col s4">
					          <input id="description_name_ru" type="text" class="validate">
					          <label for="description_name_ru">Description Name RU</label>
					        </div>
				      </div>
				    </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection