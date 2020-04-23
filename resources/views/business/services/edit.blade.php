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

                    <form action="{{ url('/business/services' . (isset($service) ? '/' . $service -> id : '/')) }}" method="POST">
                    	@csrf
                        @if (isset($service))
                            <input type="hidden" name="_method" value="PUT">
                        @endif

                        <div class="row">
                            @foreach ($languages as $language)
                                <div class="col s4">
                                    <div class="input-field">
                                        <label for="{{ 'title_' . $language }}">{{ 'სათაური (' . $language .')' }}</label>

                                        <input
                                            type="text"
                                            id="{{ 'title_' . $language }}"
                                            name="{{ 'title_' . $language }}"
                                            value="{{ isset($service) && isset($service -> getTranslations('title')[$language]) ? $service -> getTranslations('title')[$language] : null }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            @foreach ($languages as $language)
                                <div class="col s4">
                                    <div class="input-field">
                                        <label for="{{ 'description_' . $language }}">{{ 'აღწერა_' . $language }}</label>

                                        <input
                                            type="text"
                                            id="{{ 'description_' . $language }}"
                                            name="{{ 'description_' . $language }}"
                                            value="{{ isset($service) && isset($service -> getTranslations('description')[$language]) ? $service -> getTranslations('description')[$language] : null }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <div class="input-field">
                                    <button class="btn cyan waves-effect waves-light right" type="submit" name="action">
                                        დადასტურება
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
    			</div>
    		</div>
    	</div>
    </div>
@endsection
