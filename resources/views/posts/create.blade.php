@extends('master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Upload Machine</h1>
            <div class="panel">
            <div class="panel-body">
            <div class="" role="">
                <b>General Guidelines:</b>
                <ul>
                <li>You are allowed to upload modifications of someone else's machine as long as it is not identical.</li>
                <li>There is no limitation on what counts as a modification, even a single piece difference is acceptable.</li>
                <li>If you modify someone else's machine, it is nice (but not required) to give them credit.</li>
                <li>Do not implement or post extremely offensive concepts (i.e. Holocaust or rape jokes).</li>
                <li>If possible, please avoid NSFW language and content in the title, description, or images.</li>
                <li>Moderators will remove any content regardless of rules if it more than "PG-13".</li>
                </ul>
            </div>
            <hr/>
            @if(!Auth::check())
            <div class="alert alert-danger" role="alert">
                <b>You are not logged in. If you upload without logging in, you will be not be able to edit or delete this upload.</b>
            </div>
            @endif

            {!! Form::open(['url' => 'posts', 'files'=>true]) !!}

            @include('errors/list')



            @include ('posts._form')

            {{ csrf_field() }}

            <div class="form-group">
                {!! Form::button('Upload', ['class'=> 'btn btn-primary form-control', 'type'=>'submit']) !!}
            </div>

            {!! Form::close() !!}

        </div>

        </div>
        </div>
    </div>
@stop