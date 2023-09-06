<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Transport Type';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($transport_type,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['transport_type.update', $transport_type->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['transport_type.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.transport_type.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




