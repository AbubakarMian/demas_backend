{{-- {!!dd($teacher)!!} --}}

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
        @foreach($message->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="form-group">
    <label for="pickup_location_id">Pickup Location</label>
    {!! Form::select('pickup_location_id', $location, null, [
        'class' => 'form-control pickup_location',
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
        'class' => 'form-control dropoff_location',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Dropoff Location',
        'onchange'=>'setJourneName()',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('name',' Name') !!}
    <div>
        {!! Form::text('name', null, ['class' => 'form-control location_name',
        'data-parsley-required'=>'true',
        'data-parsley-trigger'=>'change',
        'placeholder'=>'Pickup Location to Dropoff Location','required']) !!}
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

    function setJourneName(){
        // console.log('check',$('.pickup_location').find(':selected').text());
        // console.log('check',$('.dropoff_location').find(':selected').text());
        var pickup = $('.pickup_location').find(':selected').text();
        var dropoff = $('.dropoff_location').find(':selected').text();
        $('.location_name').attr('placeholder',pickup+' to '+dropoff);
    }
    function validateForm() {
        return true;
    }

</script>

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>

@endsection

