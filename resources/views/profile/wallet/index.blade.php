@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Кошелек</h1>

    </div>

    <div class="container g-pt-70 g-pb-30">
        <div class="row">
            <!-- Profile Settings -->
            <div class="col-lg-3 g-mb-50">
                <aside class="g-brd-around g-brd-gray-light-v4 rounded g-px-20 g-py-30">
                    <!-- Profile Picture -->
                    <div class="text-center g-pos-rel g-mb-30">
                        <div class="g-width-100 g-height-100 mx-auto mb-3">
                            <img class="img-fluid rounded-circle" src="#" alt="Аватар пользователя">
                        </div>

                        <span class="d-block g-font-weight-500">{{ $user->name }}</span>

                        <span class="u-icon-v3 u-icon-size--xs g-color-white--hover g-bg-primary--hover rounded-circle g-pos-abs g-top-0 g-right-15 g-cursor-pointer"
                              title="" data-toggle="tooltip" data-placement="top"
                              data-original-title="Change Profile Picture">
                  <i class="icon-finance-067 u-line-icon-pro"></i>
                </span>
                    </div>
                    <!-- End Profile Picture -->

                    <hr class="g-brd-gray-light-v4 g-my-30">

                    <!-- Profile Settings List -->
                    <ul class="list-unstyled mb-0">
                        <li class="g-pb-3">
                            <a class="d-block active align-middle u-link-v5 g-color-text g-color-primary--hover g-bg-gray-light-v5--hover g-color-primary--active g-bg-gray-light-v5--active rounded g-pa-3"
                               href="{{ route('profile.wallet') }}">
                                <span class="u-icon-v1 g-color-gray-dark-v5 mr-2"><i
                                            class="icon-finance-059 u-line-icon-pro"></i></span>
                                Ваш кошелек
                            </a>
                        </li>

                    </ul>
                    <!-- End Profile Settings List -->
                </aside>
            </div>
            <!-- End Profile Settings -->

            <!-- Wallet -->
            <div class="col-lg-9 g-mb-50">
                <!-- Balance & Rewards -->
                <div class="g-brd-around g-brd-gray-light-v4 rounded g-px-30 g-pt-30">
                    <h3 class="h5 mb-3">Кошелек</h3>

                    <!-- Balance Info -->
                    <div class="row justify-content-between">
                        <div class="col-sm-4 g-mb-30">
                            <div class="g-bg-gray-dark-v3 text-center rounded g-px-20 g-py-30">
                                <span class="d-block g-color-white g-font-weight-600 g-font-size-25 mb-1">₽ {{ $wallet->totalBalance() }}</span>
                                <span class="d-block g-color-white-opacity-0_8 g-font-size-18">Доступные средства</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Balance Info -->

                    <!-- Add Balance -->
                    <div class="g-mb-50">
                        <button class="btn g-brd-around g-brd-gray-light-v3 g-color-gray-dark-v3 g-bg-gray-light-v5 g-bg-gray-light-v4--hover g-font-size-13 rounded g-px-18 g-py-9"
                                type="button">
                            <i class="align-middle g-font-size-16 mr-2 icon-finance-210 u-line-icon-pro"></i>
                            Пополнить
                        </button>
                    </div>
                    <!-- End Add Balance -->

                    <div class="row">
                        <div class="col-sm-4 g-mb-30">
                            <!-- Payment History -->
                            <h3 class="h5 mb-3">История платежей</h3>
                            @if($user->completed_transactions->count())
                                @foreach($user->completed_transactions as $transaction)
                                    <p class="mb-0">Сумма: {{ $transaction->amount }}</p>
                                    <a class="g-font-size-13" href="#">Подробности платежа</a>
                            @endforeach
                        @endif
                        <!-- End Payment History -->
                        </div>

                        <div class="col-sm-8 g-mb-30">
                            <h3 class="h5 mb-3">Последине счета</h3>

                            <!-- Media -->
                            <div class="media g-mb-30">
                                <div class="d-flex mr-4">
                                  <span class="u-icon-v2 u-icon-size--sm g-brd-gray-dark-v5 g-color-main rounded-circle">
                                    <i class="icon-finance-009 u-line-icon-pro"></i>
                                  </span>
                                </div>
                                <div class="media-body">
                                    <h4 class="h6 mb-1">Новые счета</h4>
                                    <ul>
                                        @foreach($user->invoices()->new()->get() as $newInvoice)
                                            <li>#{{ $newInvoice->id }} на сумму: {{ $newInvoice->amount }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- End Media -->

                            <!-- Media -->
                            <div class="media g-mb-30">
                                <div class="d-flex mr-4">
                                  <span class="u-icon-v2 u-icon-size--sm g-brd-gray-dark-v5 g-color-main rounded-circle">
                                    <i class="icon-real-estate-040 u-line-icon-pro"></i>
                                  </span>
                                </div>
                                <div class="media-body">
                                    <h4 class="h6 mb-1">Отмененные счета</h4>
                                    <ul>
                                        @foreach($user->invoices()->canceled()->get() as $newInvoice)
                                            <li>#{{ $newInvoice->id }} на сумму: {{ $newInvoice->amount }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- End Media -->
                        </div>
                    </div>
                </div>
                <!-- End Balance & Rewards -->
            </div>
            <!-- End Wallet -->
        </div>
    </div>
@endsection