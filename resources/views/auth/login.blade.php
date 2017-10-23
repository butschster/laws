@extends('layouts.clean')

@section('content')
    <!-- Login -->
    <section class="g-min-height-100vh g-flex-centered g-bg-lightblue-radialgradient-circle">

        <div class="container g-pt-100 g-pb-20">
            <div class="row justify-content-between">
                <div class="col-md-6 col-lg-5 flex-md-unordered align-self-center g-mb-80">
                    <div class="u-shadow-v21 g-bg-white rounded g-pa-50">
                        <header class="text-center mb-4">
                            <h2 class="h2 g-color-black g-font-weight-600">Авторизация</h2>
                        </header>

                        <!-- Form -->
                        <form class="g-py-15" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="mb-4 form-group{{ $errors->has('email') ? ' u-has-error-v1' : '' }}">
                                <div class="input-group">
                                    <span class="input-group-addon g-width-45 ">
                                        <i class="icon-user"></i>
                                    </span>

                                    <input class="form-control g-py-15 g-px-15" type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" autofocus>
                                </div>

                                @if ($errors->has('email'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="g-mb-30{{ $errors->has('password') ? ' u-has-error-v1' : '' }}">
                                <div class="input-group">
                                    <span class="input-group-addon g-width-45">
                                        <i class="icon-lock"></i>
                                    </span>

                                    <input class="form-control g-py-15 g-px-15" type="password" placeholder="Password" name="password">
                                </div>
                                @if ($errors->has('password'))
                                <span class="form-control-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif

                                <div class="row justify-content-between mt-4">
                                    <div class="col align-self-center">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Запомнить меня</span>
                                        </label>
                                    </div>
                                    <div class="col align-self-center text-right">
                                        <a class="g-font-size-12" href="{{ route('password.request') }}">Забыли пароль?
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-5">
                                <button class="btn btn-block u-btn-primary rounded g-py-13" type="submit">Войти</button>
                            </div>
                        </form>
                        <!-- End Form -->

                        <footer class="text-center">
                            <p class="g-color-gray-dark-v5 mb-0">
                                У вас нет аккаунта?
                                <a class="g-font-weight-600" href="{{ route('register') }}">Регистрация</a>
                            </p>
                        </footer>
                    </div>
                </div>

                <div class="col-md-6 flex-md-first align-self-center g-mb-80">
                    @include('auth.components.login-text')
                </div>
            </div>
        </div>

    </section>
    <!-- End Login -->
@endsection
