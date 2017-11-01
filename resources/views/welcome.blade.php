@extends('layouts.app')

@section('content')
    <section id="about-section" class="g-pb-100">
        <div class="container">
                <!-- Banners -->
                <div class="row align-items-stretch mt-5">
                    <div class="col-lg-6 g-mb-30">
                        <!-- Article -->
                        <article class="text-center g-color-white g-overflow-hidden">
                            <div class="g-min-height-200 g-flex-middle g-bg-cover g-bg-size-cover g-bg-bluegray-opacity-0_3--after g-transition-0_5" data-bg-img-src="{{ asset('images/tmp/calculator.jpg') }}">
                                <div class="g-flex-middle-item g-pos-rel g-z-index-1 g-py-50 g-px-20">
                                    <h3>Расчет процентов по займу</h3>
                                    <hr class="g-brd-3 g-brd-white g-width-30 g-my-20">
                                    <a class="btn btn-md u-btn-outline-white" href="{{ route('claim.calculator.fine') }}">Перейти</a>
                                </div>
                            </div>
                        </article>
                        <!-- End Article -->
                    </div>

                    <div class="col-lg-6 g-mb-30">
                        <!-- Article -->
                        <article class="text-center g-color-white g-overflow-hidden">
                            <div class="g-min-height-200 g-flex-middle g-bg-cover g-bg-size-cover g-bg-bluegray-opacity-0_3--after g-transition-0_5" data-bg-img-src="{{ asset('images/tmp/calculator1.jpg') }}">
                                <div class="g-flex-middle-item g-pos-rel g-z-index-1 g-py-50 g-px-20">
                                    <h3>Расчет процентов по займу по ст.395</h3>
                                    <hr class="g-brd-3 g-brd-white g-width-30 g-my-20">
                                    <a class="btn btn-md u-btn-outline-white" href="{{ route('claim.calculator.395') }}">Перейти</a>
                                </div>
                            </div>
                        </article>
                        <!-- End Article -->
                    </div>
            </div>

            <div class="row align-items-stretch">
                <div class="col-lg-6 g-mb-30">
                    <!-- Article -->
                    <article class="text-center g-color-white g-overflow-hidden">
                        <div class="g-min-height-200 g-flex-middle g-bg-cover g-bg-size-cover g-bg-bluegray-opacity-0_3--after g-transition-0_5" data-bg-img-src="{{ asset('images/tmp/claim.jpg') }}">
                            <div class="g-flex-middle-item g-pos-rel g-z-index-1 g-py-50 g-px-20">
                                <h3>Составление иска по займу</h3>
                                <hr class="g-brd-3 g-brd-white g-width-30 g-my-20">
                                <a class="btn btn-md u-btn-outline-white" href="{{ route('claim') }}">Перейти</a>
                            </div>
                        </div>
                    </article>
                    <!-- End Article -->
                </div>
            </div>
        </div>
                <!-- End Banners -->
        </div>
    </section>
@endsection