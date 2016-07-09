@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-md-8 col-lg-6 pad-sides">
            <div>
                {!! $content !!}
            </div>
        </div>
        <div class="col-md-4 col-lg-6 pad-sides">
            <img src="img/9.jpg" class="img-responsive"/>
        </div>
    </div>
@endsection