<?php
if($control == 'edit'){
    $heading = 'Edit';
}
else{
    $heading = 'Add User';
}
?>
@extends('layouts.default_edit')
@section('heading')
    {!! $heading !!}
@endsection
@section('leftsideform')

    @if($control == 'edit')
        {!! Form::model($user,['id'=>'my_form', 'method' => 'POST', 'route' =>
                  ['user.update', $user->id],'files'=>true]) !!}
    @else
        {!! Form::open(['id'=>'my_form','method' => 'POST', 'route' => ['user.save' ], 'files'=>true]) !!}
    @endif
    @include('admin.user.partial.form')
    {!!Form::close()!!}



@endsection
{!!Form::close()!!}




