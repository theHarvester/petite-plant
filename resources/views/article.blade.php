@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-12 imgs-responsive">
            {!! $content !!}
        </div>
    </div>
    <div class="row row-pad">
        <div class="col-lg-12 imgs-responsive">
            <h3>More Articles</h3>
            <ul class="article-list">
                @foreach($allArticles as $article)
                    <li>{{ link_to('article/'.$article->slug, $article->title) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection