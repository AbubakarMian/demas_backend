<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Journey_slot';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($journey_slot,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['journey_slot.update', $journey_slot->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['journey_slot.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.journey_slot.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




