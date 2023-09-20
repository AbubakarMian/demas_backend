<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Sale Agent';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($sale_agent,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['sale_agent.update', $sale_agent->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['sale_agent.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.sale_agent.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




