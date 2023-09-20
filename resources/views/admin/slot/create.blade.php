<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Slot';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($slot,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['slot.update', $slot->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['slot.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.slot.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




