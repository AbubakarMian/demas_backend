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
    {!! Form::label('pickup_location_id',' Pick Up Location') !!}
    <div>
        {!! Form::text('pickup_location_id', null, ['class' => 'form-control',
        'data-parsley-required'=>'true',
        'data-parsley-trigger'=>'change',
        'placeholder'=>'Pick Up Location','required',
        'maxlength'=>"100"]) !!}
    </div>
</div> --}}
<div class="form-group">
    <label for="pickup_location_id">Pickup Location</label>
    {!! Form::select('pickup_location_id', $location, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Pickup Location',
        'required',
        'maxlength' => '100',
    ]) !!}
</div>
<div class="form-group">
    <label for="dropoff_location_id">Dropoff Location</label>
    {!! Form::select('dropoff_location_id', $location, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Dropoff Location',
        'required',
        'maxlength' => '100',
    ]) !!}
</div>
{{-- <div class="form-group">
    {!! Form::label('dropoff_location_id',' DropOff Location') !!}
    <div>
        {!! Form::text('dropoff_location_id', null, ['class' => 'form-control',
        'data-parsley-required'=>'true',
        'data-parsley-trigger'=>'change',
        'placeholder'=>'DropOff Location','required',
        'maxlength'=>"100"]) !!}
    </div>
</div> --}}


















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

