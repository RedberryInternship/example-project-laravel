@extends('business.authenticate')
@section('css')
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/register.css">
@endsection
@section('body')
	<div id="register-page" class="row">
        <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 register-card bg-opacity-8">
            <form class="login-form" method="POST" action="/business/register">
                @csrf
                <div class="row">
                    <div class="input-field col s12">
                        <h5 class="ml-4">Register</h5>
                        <p class="ml-4">Join to our community now !</p>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">person</i>
                        <input id="first_name" type="text" name="first_name">
                        <label for="first_name" class="center-align">First Name</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">person</i>
                        <input id="last_name" type="text" name="last_name">
                        <label for="last_name" class="center-align">Last Name</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">phone</i>
                        <input id="phone" type="text" name="phone_number" autocomplete="off" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" maxlength="11">
                        <label for="phone" class="center-align">Phone Number</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">mail_outline</i>
                        <input id="email" type="email" name="email">
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password" type="password" name="password1">
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password-again" type="password" name="password2">
                        <label for="password-again">Password again</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="submit" name="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <p class="margin medium-small"><a href="/business/login">Already have an account? Login</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection