@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-offset-2 col-lg-8">
            <h1>{!! array_get($article, 'title') !!}</h1>
        </div>
        <div class="col-lg-offset-1 col-lg-8 imgs-responsive row-pad">
            {!! $content !!}
        </div>
        <div class="col-lg-2 imgs-responsive row-pad">
            Tags, tags, tags, tags, tags, tags
        </div>
    </div>
    <div class="row row-pad">
        <div class="col-lg-offset-2 col-lg-8 imgs-responsive">
            @include('shared/more-articles')
        </div>
    </div>
@endsection