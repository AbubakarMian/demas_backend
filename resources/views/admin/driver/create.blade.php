<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Driver';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($driver,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['driver.update', $driver->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['driver.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.driver.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




