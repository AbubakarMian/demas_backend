<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Driver Journey';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($driver_journey,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['driver_journey.update', $driver_journey->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['driver_journey.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.driver_journey.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




