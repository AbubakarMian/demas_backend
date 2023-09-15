{{-- {!!dd($teacher)!!} --}}

<style>
select#gender {
    width: 100%;
    height: 40px;
        border: 1px solid #e3e6f3;
}
.medsaveclick {
    padding-top: 10px !important;
    color: white;
}
    </style>

@if ($message = Session::get('error'))

<div class="alert alert-danger">
    <ul>
        @foreach($message->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
{{-- <div class="form-group">
    <label for="journey_id">Journey</label>
    {!! Form::select('journey_id', $journey, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Journey',
        'required',
        'maxlength' => '100',
    ]) !!}
</div> --}}
<div class="form-group">
    {!! Form::label('from_date',' Start Date') !!}
    <div>
        {!! Form::date('from_date', null, ['class' => 'form-control',
        'data-parsley-required'=>'true',
        'data-parsley-trigger'=>'change',
        'placeholder'=>'Enter Start Date','required',
        'maxlength'=>"100"]) !!}
    </div>
</div>

{{-- <div class="form-group">
    {!! Form::label('gender','Gender') !!}
    <div>

        {!! Form::select('gender', array('Male'=>'Male','Female'=>'Female'), [
            'class' => 'form-control',
        'data-parsley-required'=>'true',
        'data-parsley-trigger'=>'change',
        'placeholder'=>'Enter gender','required',
        'maxlength'=>"100"]) !!}
    </div>

</div> --}}
<div class="form-group">
    {!! Form::label('to_date','End Date') !!}
    <div>
        {!! Form::date('to_date',  null, ['class' => 'form-control',
        'data-parsley-required'=>'true',
        'data-parsley-trigger'=>'change',
        'placeholder'=>'Enter End Date','required',
        'maxlength'=>"100"]) !!}
    </div>
</div>



















<span id="err" class="error-product"></span>


<div class="form-group col-md-12">
</div>





<div class="col-md-5 pull-left">
    <div class="form-group text-center">
        <div>
            {!! Form::submit('Save', ['class' => ' btn-block btn-lg btn-parsley medsaveclick', 'onblur' => 'return validateForm();']) !!}
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

