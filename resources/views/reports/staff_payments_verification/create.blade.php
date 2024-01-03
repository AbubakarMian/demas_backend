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
        {!! Form::model($staff_payments_incoming,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['staff_payments_incoming.update', $staff_payments_incoming->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['staff_payments_incoming.save' ], 'files'=>true]) !!}
    @endif
    @include('reports.staff_payments_incoming.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




