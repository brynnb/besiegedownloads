@extends('master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Report Content</h1>
            <div class="panel">
                <div class="panel-body">
                    <div class="" role="">
                        <b>Valid reasons to report content:</b>
                        <ol>
                            <li>Extremely offensive concepts (i.e. Holocaust or rape jokes)</li>
                            <li>Hate speech, slurs, or racism (do not report simple swear words)</li>
                            <li>Images or videos that are more than "PG-13" or any image that is not mostly an in-game image of the machine</li>
                            <li>The attached .bsg is not actually a .bsg file or does not match image/description</li>
                        </ol>
                        <b>Please keep in mind:</b>
                        <ul>
                            <li>People are allowed to upload modifications of someone else's machine as long as it is not identical.</li>
                            <li>There is no limitation on what counts as a modification, even a single piece difference is acceptable.</li>
                            <li>If someone modifies someone else's machine, it is not required to give them credit (but it's nice to do so).</li>
                            <li>If someone posts a machine that is not theirs but has not been posted on this website yet, do not report it. Leave a comment giving attribution to the original creator.</li>
                            <li><b>PLEASE DO NOT REPORT MACHINES FOR COPYING OTHERS</b> - if someone did not give you attribution, leave a comment.</li>
                        </ul>
                    </div>
                    <hr/>

                    {!! Form::open(['url' => 'reports']) !!}
                    {!! Form::hidden('post_id', $value = $id) !!}
                    @include('errors/list')

                    <div class="form-group">
                        {!! Form::label('text', 'Specify which guideline(s) above this post violates:') !!}
                        {!! Form::textarea('text', null, ['class'=>'form-control', 'rows'=>'5', 'placeholder'=>'Please only report an item if it violates any of the four guidelines above']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('check', 'What is 2+2?') !!}
                        {!! Form::text('check', null, ['class'=>'form-control','placeholder'=>'To prevent spam']) !!}
                    </div>
                    <div class="form-group">

                        {!! Form::label('agree', 'I agree to be banned on all accounts accessed on this IP address if I report a post for any reason other than the four listed at the top of this page.') !!}

                    </div>

                    {!! Form::button('Agree and Submit Report', ['class'=> 'btn btn-primary form-control pull-right', 'type'=>'submit']) !!}

                    {!! Form::close() !!}
                </div>
            </div>


        </div>

    </div>
@stop