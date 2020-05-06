@extends('business.authenticate')

@section('css')
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/pages/login.css">
@endsection

@section('body')
    <div id="login-page" class="row">
        <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
            <form class="login-form" action="/business/auth" method="POST">
                @csrf

                <div class="row">
                    <div class="input-field col s12">
                        <h5 class="ml-4">ავტორიზაცია</h5>
                    </div>
                </div>

                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">email</i>
                        <input id="email" type="text" name="email" autocomplete="off">
                        <label for="email" class="center-align">ელ. ფოსტა</label>
                    </div>
                </div>

                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock_outline</i>
                        <input id="password" type="password" name="password">
                        <label for="password">პაროლი</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input type="submit" value="ავტორიზაცია" name="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
