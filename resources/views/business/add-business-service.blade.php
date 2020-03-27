@extends('business.master')
@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection
@section('body')
<div class="row">
	<div class="col s12">
		<div class="card card-tabs">
			<div class="card-content">
                <h4 class="card-title">სერვისის დამატება</h4>
                <form method="POST" action="/business/add-business-service" enctype="multipart/form-data">
                	@csrf
                    <div class="row col s4">
                        <div class="input-field col s12">
                            <input type="text" id="fn" name="title_ge">
                            <label for="fn" class="">Title_ge</label>
                        </div>
                    </div>
                    <div class="row col s4">
                        <div class="input-field col s12">
                            <input type="text" id="fn" name="title_en">
                            <label for="fn" class="">Title_en</label>
                        </div>
                    </div>
                    <div class="row col s4">
                        <div class="input-field col s12">
                            <input type="text" id="fn" name="title_ru">
                            <label for="fn" class="">Title_ru</label>
                        </div>
                    </div>
                    <div class="row col s12">
                        <div class="input-field col s4">
                            <textarea id="message" class="materialize-textarea" name="description_ge"></textarea>
                            <label for="message">Description_ge</label>
                        </div>
                        <div class="input-field col s4">
                            <textarea id="message" class="materialize-textarea" name="description_en"></textarea>
                            <label for="message">Description_en</label>
                        </div>
                        <div class="input-field col s4">
                            <textarea id="message" class="materialize-textarea" name="description_ru"></textarea>
                            <label for="message">Description_ru</label>
                        </div>
                    </div>
                    <div class="row col s12">
                        <div class="col m6 s12 file-field input-field">
                            <div class="btn float-right">
                                <span>File</span>
                                <input type="file" name="image">
                            </div>
                            <div class="file-path-wrapper" id="file">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
@endsection