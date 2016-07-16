@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-6">
            <div class="row-pad">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#uploadImageModal">
                    Upload Images
                </button>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#insertImageModal">
                    Insert Image
                </button>
            </div>

            {!! Form::open(['url' => 'admin/drafts/save', 'method' => 'post']) !!}
            {{Form::label('title', 'Title')}}
            {{Form::text('title', array_get($article, 'title'), ['class' => 'form-control'])}}

            {{Form::label('slug', 'Slug')}}
            {{Form::text('slug', array_get($article, 'slug', 'my-slug-here'), ['class' => 'form-control'])}}

            {{Form::label('thumbnail', 'Thumbnail URL')}}
            {{Form::text('thumbnail', array_get($article, 'thumbnail'), ['class' => 'form-control'])}}

            {{Form::label('summary', 'Article Summary')}}
            {{Form::textarea('summary', array_get($article, 'summary'), ['class' => 'form-control'])}}

            {{Form::label('content', 'Article Content')}}
            {{Form::textarea('content', array_get($article, 'content'), ['class' => 'form-control', 'id' => 'draft-content'])}}

            {{Form::label('published_at', 'Published date? (set to future to schedule it)')}}
            {{Form::date('published_at', (new DateTime(array_get($article, 'published_at', null))), ['class' => 'form-control'])}}

            {{Form::label('is_published', 'Ready to publish?')}}
            {{Form::checkbox('is_published', 'is_published', !!array_get($article, 'published_at', false))}}
            {{Form::hidden('article_id', array_get($article, 'id'))}}
            {{Form::token()}}
            <div class="row-pad">
                {{Form::submit('Save', ['class' => 'btn btn-default'])}}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-lg-6">
            <div id="draft-preview" class="imgs-responsive"></div>
        </div>

        <div id="uploadImageModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload Images</h4>
                    </div>
                    <div class="modal-body">
                        <!-- D&D Markup -->
                        <div id="drag-and-drop-zone" class="uploader">
                            <div>Drag &amp; Drop Images Here</div>
                            <div class="or">-or-</div>
                            <div class="browser">
                                <label>
                                    <span>Click to open the file Browser</span>
                                    <input type="file" name="files[]" multiple="multiple" title='Click to add Files'>
                                </label>
                            </div>
                            <div id="fileList">
                                <!-- Files will be places here -->
                            </div>
                            <div id="debug">
                                <h2>Debug</h2>

                                <div>
                                    <ul>
                                        <!-- Debug lines will be added here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="insertImageModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Insert Image</h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <h3>Thumbnail URL</h3>
                            <code id="img-thumb">
                            </code>
                        </div>
                        <div>
                            <h3>Markdown</h3>
                            <code id="img-code">
                            </code>
                        </div>
                        <div id="img-list">
                            @foreach($images as $image)
                                <div class="thumbnail pull-left">
                                    <img src="{{$image}}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
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
            }, 4000);

            var imgCode = $('#img-code');
            var imgThumb = $('#img-thumb');
            $('#img-list').find('.thumbnail').click(function () {
                imgCode.text('![Image Alt Text Here](' + $(this).find('img').attr('src') + ' "Image Title Here")');
                imgThumb.text($(this).find('img').attr('src'));
            });

            //-- Some functions to work with our UI
            function add_log(message) {
                console.log(message);
                var template = '<li>' + message + '</li>';

                $('#debug').find('ul').prepend(template);
            }

            function add_file(id, file) {
//        var template = '' +
//          '<div class="file" id="uploadFile' + id + '">' +
//            '<div class="info">' +
//              '#1 - <span class="filename" title="Size: ' + file.size + 'bytes - Mimetype: ' + file.type + '">' + file.name + '</span><br /><small>Status: <span class="status">Waiting</span></small>' +
//            '</div>' +
//            '<div class="bar">' +
//              '<div class="progress" style="width:0%"></div>' +
//            '</div>' +
//          '</div>';

//          $('#fileList').prepend(template);
            }

            function update_file_status(id, status, message) {
                $('#uploadFile' + id).find('span.status').html(message).addClass(status);
            }

            function update_file_progress(id, percent) {
                $('#uploadFile' + id).find('div.progress').width(percent);
            }

            // Upload Plugin itself
            $('#drag-and-drop-zone').dmUploader({
                url: '/admin/images/save',
                dataType: 'json',
                allowedTypes: 'image/*',
                extraData: {
                    _token: $('input[name="_token"]').val()
                },
                onInit: function () {
                    add_log('Penguin initialized :)');
                },
                onBeforeUpload: function (id) {
                    add_log('Starting the upload of #' + id);

                    update_file_status(id, 'uploading', 'Uploading...');
                },
                onNewFile: function (id, file) {
                    add_log('New file added to queue #' + id);

                    add_file(id, file);
                },
                onComplete: function () {
                    add_log('All pending tranfers finished');
                },
                onUploadProgress: function (id, percent) {
                    var percentStr = percent + '%';

                    update_file_progress(id, percentStr);
                },
                onUploadSuccess: function (id, data) {
                    add_log('Upload of file #' + id + ' completed');

                    add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));

                    update_file_status(id, 'success', 'Upload Complete');

                    update_file_progress(id, '100%');
                },
                onUploadError: function (id, message) {
                    add_log('Failed to Upload file #' + id + ': ' + message);

                    update_file_status(id, 'error', message);
                },
                onFileTypeError: function (file) {
                    add_log('File \'' + file.name + '\' cannot be added: must be an image');

                },
                onFileSizeError: function (file) {
                    add_log('File \'' + file.name + '\' cannot be added: size excess limit');
                },
                onFallbackMode: function (message) {
                    alert('Browser not supported(do something else here!): ' + message);
                }
            });
        });
    </script>
@endsection
