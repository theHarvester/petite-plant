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

            <form method="POST" class="em_wfe_form" id="" name=""
                  action="http://www.vision6.com.au/em/forms/subscribe.php?db=364457&s=97991&a=59665&k=ff345a4&va=5"
                  enctype="multipart/form-data"><input type="hidden" name="webform_submit_catch" value=""/><input
                        type="hidden" name="sf_catch" value=""/>

                <div rel="5" class="wfe_component" id="810448" style="padding-top: 30px;">
                    <div class="input-group">
                        {{--<span class="input-group-addon" id="sizing-addon2">@</span>--}}
                        <input type="text" class="form-control" placeholder="Email" aria-describedby="sizing-addon2"
                               value="" id="em_wfs_formfield_3119420"
                               name="em_wfs_formfield_3119420" validation="1"
                               ftype="1" maxlength=80 size=0>
                        <span class="input-group-btn">
                            <button class="wfe_button btn btn-info card-btn" type="submit" title="Submit Form">Get plant news</button>
                        </span>
                    </div>
                </div>
                <!-- Do not remove, as this DIV and INPUT help fight against spam. -->
                <div style="display:none;position:absolute;left:-10000px;top:-10000px;"><label for="webform_7d9bf50"
                                                                                               style="display:none;visibility:hidden;width:0px;height:0px;font-size:1px;">Ignore</label><input
                            type="text" id="webform_7d9bf50" name="webform_7d9bf50" value=""
                            style="display:none;visibility:hidden;width:0px;height:0px;font-size:1px;"/></div>
            </form>
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