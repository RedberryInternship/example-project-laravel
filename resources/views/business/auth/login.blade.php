@extends('business.auth.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="/app-assets/css-rtl/pages/login.css">
    <link rel="stylesheet" type="text/css" href="/css/business/login.css">
@endsection

@section('body')
    <div id="login-page" class="row">
        <h1 class="login-title">@lang('business.login.title')</h1>
        <div class="col s12 m6 l3 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
            <form class="login-form" action="/business/auth" method="POST">
                @csrf

                <div class="row">
                    <div class="input-field col s12">
                        <h5 class="ml-4">@lang('business.login.auth')</h5>
                    </div>
                </div>

                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">email</i>
                        <input id="email" type="text" name="email" autocomplete="off" value="{{ old('email') }}" required>
                        <label for="email" class="center-align" class="input-lab">@lang('business.login.mail')</label>
                    </div>
                </div>

                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password" type="password" autocomplete="off" name="password" required>
                        <label for="password" class="input-lab">@lang('business.login.password')</label>
                    </div>
                </div>

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <p class="auth-error">{{ $error }}</p>
                    @endforeach
                @endif

                <div class="row">
                    <div class="input-field col s12">
                        <button type="submit" class="btn waves-effect waves-light border-round col s6 custom-auth-btn">
                            @lang('business.login.auth')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
