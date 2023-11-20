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
    .remove_btn {
    position: absolute;
    top: -11px;
    right: -10px;
    background: red;
    text-align: right;
    padding-right: 5px;
    font-size: 15px;
    color: white;
    /* font-weight: bold; */
    cursor: pointer;
    border-radius: 50px;
    width: 20px;
    height: 20px;
}

.car_images.col-md-2 {
    margin: 13px 3px;
    border: solid 1px #996418;
    border-radius: 10px;
    padding: 5px;
}
.car_images {
    position: relative;
    display: inline-block;
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
<div class="form-group">
    <label for="user_id">Select Staff</label>
    {!! Form::select('user_id', $user, null, [
        'class' => 'form-control pickup_location',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Staff',
        'required',
        'maxlength' => '100',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('amount', 'Amount') !!}
    <div>
        {!! Form::number('amount', null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Enter amount',
            'required',
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
    

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
@endsection
