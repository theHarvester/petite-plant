@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-6">
            {!! Form::open(['url' => $submitTo, 'method' => 'post']) !!}
            {{Form::label('content', 'Article Content')}}
            {{Form::textarea('content', $content, ['class' => 'form-control', 'id' => 'draft-content'])}}
            {{Form::token()}}
            <div class="row-pad">
                {{Form::submit('Save', ['class' => 'btn btn-default'])}}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-lg-6">
            <div id="draft-preview"></div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/js/dmuploader.min.js"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            // Draft rendering
            var textArea = $("textarea#draft-content");
            var preview = $("#draft-preview");
            setInterval(function () {
                $.post("/admin/drafts/render", {
                    content: textArea.val(),
                    _token: $('input[name="_token"]').val()
                }, function (data) {
                    preview.html(data.result);
                });
            }, 1000);
        });
    </script>
@endsection
