@extends('master')

@section('content')
    <ul>
    @if($reports->count() == 0)
        <br/><br/>
        <center><h3>No pending reports. Hooray! :)</h3></center>
        <br/><br/>
    @endif
    @foreach($reports as $report)

        <li>[{{$report->created_at}}] Post(<a href="{{action('PostsController@show',$report->post_id)}}">link to post</a>)(<a href="{{action('ReportsController@delete',$report->id)}}">delete</a>): {{$report->text}}</li>

    @endforeach
    </ul>
@endsection