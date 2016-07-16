@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-md-8">
            <img src="img/hero3.jpg" class="img-responsive" style="background-color: rgba(0,0,0,0.5)">
        </div>

        <div class="col-md-4 feature-tagline" style="text-align: center">
            <p style="font-family: 'Amatic SC', cursive; font-size: 3em">Cute plants</p>

            <p style="font-family: 'Amatic SC', cursive; font-size: 2em">- for -</p>

            <p style="font-family: 'Rye', cursive; font-size: 2.5em;">SMALL&nbsp;SPACES</p>

            <div style="padding-top: 30px;">
                @include('shared/newsletter')
            </div>
        </div>
    </div>
    <div class="row row-pad">
        <div class="col-md-4 img-responsive">
            <div class="card-bg" style="background-image: url(img/1.jpg);">
                <div class="card">
                    {{ link_to('/gallery', 'View Gallery', ['class' => 'btn btn-info card-btn btn-lg']) }}
                </div>
            </div>
        </div>
        <div class="col-md-4 img-responsive">
            <div class="card-bg" style="background-image: url(img/6.jpg);">
                <div class="card">
                    {{ link_to('/workshops', 'Workshops', ['class' => 'btn btn-info card-btn btn-lg']) }}
                </div>
            </div>
        </div>
        <div class="col-md-4 img-responsive">
            <div class="card-bg" style="background-image: url(img/3.jpg);">
                <div class="card">
                    {{ link_to('/about', 'About', ['class' => 'btn btn-info card-btn btn-lg']) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="application/javascript">
        $(document).ready(function () {

            var setHeightToWidth = function (selector) {
                $(selector).css({'height': $(selector).width() + 'px'});
            };

            var updateHeight = function () {
                setHeightToWidth('.card-bg');
                setHeightToWidth('.card');
            };

            setTimeout(updateHeight, 0);

            $(window).resize(function () {
                setTimeout(updateHeight, 0);
            });
        });
    </script>
@endsection