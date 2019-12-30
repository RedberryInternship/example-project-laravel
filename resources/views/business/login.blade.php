@extends('business.authenticate')
@section('css')
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/pages/login.css">
@endsection
@section('body')
    <div id="login-page" class="row">
        <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
            <form class="login-form">
                <div class="row">
                    <div class="input-field col s12">
                        <h5 class="ml-4">Sign in</h5>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">phone</i>
                        <input id="phone_number" type="text" name="phone_number" autocomplete="off" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" maxlength="11">
                        <label for="phone_number" class="center-align">Phone Nubmer</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password" type="password">
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12 ml-2 mt-1">
                        <p>
                            <label>
                                <input type="checkbox" />
                                <span>Remember Me</span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <a href="index.html" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Login</a>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                        <p class="margin medium-small"><a href="/business/register">Register Now!</a></p>
                    </div>
                    <div class="input-field col s6 m6 l6">
                        <p class="margin right-align medium-small"><a href="/business/forgot-password">Forgot password ?</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection