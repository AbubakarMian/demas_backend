<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Location';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($location,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['location.update', $location->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['location.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.location.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




