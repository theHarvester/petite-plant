@extends('layouts.master')

@section('main-content')
    @foreach($allArticles as $article)
        <div class="row row-pad">
            <div class="col-xs-4 col-lg-3 pad-sides">
                <img src="{{ array_get($article, 'thumbnail') }}" class="img-responsive"/>
            </div>

            <div class="col-xs-8 col-lg-9 pad-sides">
                <h3>{{ array_get($article, 'title') }}</h3>
                <p>test safsdf sdfd sf dsf sdf dsf dsf ds fsd f sdf sdf ds f sdf ds fsd fsd fs df sdf ds fsd f sdf</p>
                <a href="/article/{{ array_get($article, 'slug') }}">Read More</a>
            </div>

        </div>

        <hr>
    @endforeach


    <div class="row row-pad">
        <div class="col-xs-4 col-lg-3 pad-sides">
            <img src="img/10.jpg" class="img-responsive"/>
        </div>

        <div class="col-xs-8 col-lg-9 pad-sides">
            <h3>Test</h3>
            <p>test safsdf sdfd sf dsf sdf dsf dsf ds fsd f sdf sdf ds f sdf ds fsd fsd fs df sdf ds fsd f sdf</p>
            <a href="#">Read More</a>
        </div>

    </div>
@endsection