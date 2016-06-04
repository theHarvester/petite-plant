@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-12">
            <h3>Drafts</h3>
            <ul>
                @foreach($drafts as $draft)
                    <li>{!! link_to('admin/drafts/edit/'.$draft->id, $draft->title) !!}</li>
                @endforeach
            </ul>
        </div>

        <div class="col-lg-12">
            <h3>Published Articles</h3>
            <ul>
                @foreach($published as $article)
                    <li>{!! link_to('admin/drafts/edit/'.$article->id, $article->title) !!}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-12">
            {!! link_to_route('new-draft', 'New Draft') !!}
        </div>
    </div>
@endsection
