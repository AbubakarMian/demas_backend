<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Transport Journey Prices';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($transport_journey_prices,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['transport_journey_prices.update', $transport_journey_prices->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['transport_journey_prices.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.transport_journey_prices.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




