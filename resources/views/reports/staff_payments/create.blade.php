<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add Staff Payments';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($staff_payments,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['staff_payments.update', $staff_payments->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['staff_payments.save' ], 'files'=>true]) !!}
    @endif
    @include('reports.staff_payments.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




