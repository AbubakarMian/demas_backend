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
.btn_gp {
    border: solid 2px #a4610d;
    border-radius: 20px;
    padding: 1px;
}
label.btn.btn-secondary.paid_admin.active {
    background-color: #a4610d;
    color: white;
    font-weight: bold;
    border-bottom-left-radius: 20px;
    border-top-left-radius: 20px;
}
label.btn.btn-secondary.recieved_admin.active {
    background-color: #a4610d;
    color: white;
    font-weight: bold;
    border-bottom-right-radius: 20px;
    border-top-right-radius: 20px;
}
label.btn.btn-secondary.paid_admin {
    color: #a4610d;
    font-weight: bold;
}
label.btn.btn-secondary.recieved_admin {
    color: #a4610d;
    font-weight: bold;
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
{{-- <div class="form-group">
    <label for="user_id">Select Payment Type </label>
    {!! Form::select('payment_type ', $payment_type , null, [
        'class' => 'form-control pickup_location',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select payment Type ',
        'required',
        'maxlength' => '100',
    ]) !!}
</div> --}}

<div class="form-group">
    {!! Form::label('details ', 'details ') !!}
    <div>
        {!! Form::textarea('detail ', null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Enter details ',
            'required',
        ]) !!}
    </div>
</div>


<label for="transport_type_id">Upload Recipt Image</label>


<input type="file" accept="image/*" class="form-control prof_box crop_upload_image" image_width="500" image_height="225"
    aspect_ratio_width="0" aspect_ratio_height="0" multiple upload_input_by_name="recipt_url"
    onsuccess_function="show_image">
<div class="row">
    <div class="upload_images">

        @if (isset($staff_payments->receipt_url))
                <div class="car_images col-md-2">
                    <div class="remove_btn" onclick="remove_image(this)">X</div>

                    <img src="{!! $image !!}">
                    <input type="hidden" name="recipt_url" value="{!! $image !!}">
                </div>
        @endif

    </div>
</div>

<br>
<div class="btn-group btn-group-toggle btn_gp" data-toggle="buttons">
    <label class="btn btn-secondary paid_admin active">
      <input type="radio"  name="payment_type" value="withdrawal" id="option1" checked>Agent Cash Widdrwawl
    </label>
    <label class="btn btn-secondary recieved_admin">
      <input type="radio"  name="payment_type" value="deposit" id="option2"> Agent Cash Deposit
    </label>
  
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
        function show_image(image) {
            var image_key = $(".recipt_url").length;
            var img = `
            <div class="car_images col-md-2">
                <div class="remove_btn" onclick="remove_image(this)">X</div>
                <img src="` + image + `">
                <input type="hidden" name="recipt_url" value="` + image + `">
            </div>
            `;
            $('.upload_images').append(img);
        }

        function remove_image(e) {
            $(e).parent().remove();
        }

        function validateForm() {
            return true;
            var total_images = $(".recipt_url").length;
            if (total_images) {
                return true;
            } else {
                return false;
            }
        }
    </script>

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
@endsection
