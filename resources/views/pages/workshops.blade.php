@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-6">
            <div>
                {!! $content !!}
            </div>
            @include('shared.contact-us')
        </div>
        <div class="col-lg-6">
            <img src="img/9.jpg" class="img-responsive"/>
        </div>
    </div>
@endsection