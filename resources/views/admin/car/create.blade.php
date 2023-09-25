<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Transport';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($car,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['car.update', $car->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['car.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.car.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




