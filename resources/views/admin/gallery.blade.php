@extends('layouts.master')

@section('head')
    <style type="text/css">
        .img-lightbox {
            -webkit-transition: opacity 0.3s; /* Safari */
            transition: opacity 0.3s;
        }
        .img-lightbox:hover {
            opacity: 0.8;
            cursor: pointer;
        }
    </style>
@endsection

@section('main-content')
    @foreach(array_chunk($images, 4) as $imageSet)
        <div class="row">
            @foreach($imageSet as $image)
                <div class="col-lg-3">
                    <img src="{{ $image }}" class="img-responsive img-lightbox" data-toggle="modal"
                         data-target="#lightbox">
                </div>
            @endforeach
        </div>

        <div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('js')
    <script type="application/javascript">
        $(document).ready(function () {
            var lightbox = $('#lightbox');
            var lightboxImages = $('.img-lightbox');

            if ($(window).width() < 830) {
                lightboxImages.each(function(){
                    $(this).removeAttr('data-toggle');
                });
            }

            lightboxImages.on('click', function (event) {
                var img = $(this);
                var src = img.attr('src');
                var alt = img.attr('alt');
                var css = {
                    'maxWidth': $(window).width() - 100,
                    'maxHeight': $(window).height() - 100
                };

                lightbox.find('.close').addClass('hidden');
                lightbox.find('img').attr('src', src);
                lightbox.find('img').attr('alt', alt);
                lightbox.find('img').css(css);
            });

            lightbox.on('shown.bs.modal', function (e) {
                var img = lightbox.find('img');

                lightbox.find('.modal-dialog').css({'width': img.width()});
                lightbox.find('.close').removeClass('hidden');
            });
        });
    </script>
@endsection