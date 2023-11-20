<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Order';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($order,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['order.update', $order->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['order.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.order.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




