@extends('layouts.master')

@section('main-content')
    <div class="row row-pad">
        <div class="col-lg-2 col-lg-offset-5">
            {!! Form::open(['url' => 'login', 'method' => 'post']) !!}
            {{Form::text('email')}}
            {{Form::password('password')}}
            {{Form::token()}}
            {{Form::submit('Login')}}
            {!! Form::close() !!}
        </div>
    </div>
@endsection
