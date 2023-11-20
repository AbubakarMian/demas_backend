{{-- {!!dd($slot)!!} --}}

<style>
    select#gender {
        width: 100%;
        height: 40px;
        border: 1px solid #e3e6f3;
    }

    .medsaveclick {
        /* padding-top: 10px !important; */
        color: white;
    }
</style>

@if ($message = Session::get('error'))

    <div class="alert alert-danger">
        <ul>
            @foreach ($message->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<?php
$start_date = ''; // Initialize to an empty string
$end_date = ''; // Initialize to an empty string

if (isset($slot)) {
    // Assuming $slot->start_date and $slot->end_date are the correct properties
    $start_date = Carbon::createFromTimestamp($slot->start_date);
    $start_date = $start_date->format('Y-m-d');
    $end_date = Carbon::createFromTimestamp($slot->end_date);
    $end_date = $end_date->format('Y-m-d');
}
else{
    $start_date = Carbon::now()->format('Y-m-d');
    $end_date = Carbon::now()->format('Y-m-d');
}


?>

<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    <div>
        {!! Form::date('name', [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Rabiulawal 2023-10-02 / 2023-12-30',
            'maxlength' => '225',
        ]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('from_date', 'Start Date') !!}
    <div>
        {!! Form::date('from_date', [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Enter Start Date',
            'required',
            'maxlength' => '100',
        ]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('to_date', 'End Date') !!}
    <div>
        {!! Form::date('to_date', [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Enter End Date',
            'required',
            'maxlength' => '100',
        ]) !!}
    </div>
</div>





















<span id="err" class="error-product"></span>


<div class="form-group col-md-12">
</div>





<div class="col-md-5 pull-left">
    <div class="form-group text-center">
        <div>
            {!! Form::submit('Save', [
                'class' => ' btn-block btn-lg btn-parsley medsaveclick',
                'onblur' => 'return validateForm();',
            ]) !!}
        </div>
    </div>
</div>



@section('app_jquery')
    <script>
        function validateForm() {
            return true;
        }
    </script>

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
@endsection
