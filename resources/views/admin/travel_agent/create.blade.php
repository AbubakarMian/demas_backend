<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Sale Agent';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($travel_agent,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['travel_agent.update', $travel_agent->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['travel_agent.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.travel_agent.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




