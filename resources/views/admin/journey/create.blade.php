<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Journey';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($journey,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['journey.update', $journey->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['journey.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.journey.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




