@extends('layouts.clean')

@section('content')
<!-- Signup -->
<section class="g-min-height-100vh g-flex-centered g-bg-lightblue-radialgradient-circle">
    <div class="container g-py-50">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-9 col-lg-6">
                <div class="u-shadow-v24 g-bg-white rounded g-py-40 g-px-30">
                    <header class="text-center mb-4">
                        <h2 class="h2 g-color-black g-font-weight-600">Регистрация</h2>
                    </header>

                    <!-- Form -->
                    <form class="g-py-15" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="mb-4">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">ФИО:</label>
                            <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="text" name="name" value="{{ old('name') }}" autofocus>

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>


                        <div class="mb-4{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">E-mail:</label>
                            <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="email" name="email" value="{{ old('email') }}">


                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 mb-4">
                                <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Пароль:</label>
                                <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="password" name="password">


                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-xs-12 col-sm-6 mb-4">
                                <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Подтверждение:</label>
                                <input class="form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15" type="password" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row justify-content-between mb-5">
                            <div class="col-8 align-self-center">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description"> I accept the <a href="#">Terms and Conditions</a></span>
                                </label>
                            </div>
                            <div class="col-4 align-self-center text-right">
                                <button class="btn btn-md u-btn-primary rounded g-py-13 g-px-25" type="submit">
                                    Регистрация
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- End Form -->

                    <footer class="text-center">
                        <p class="g-color-gray-dark-v5 g-font-size-13 mb-0">У вас уже есть аккаунт?
                            <a class="g-font-weight-600" href="{{ route('login') }}">Авторизация</a>
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Signup -->
@endsection
