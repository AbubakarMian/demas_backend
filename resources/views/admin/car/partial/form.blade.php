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
            @foreach ($message->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<div class="form-group">
    <label for="transport_type_id">Transport Type</label>
    {!! Form::select('transport_type_id', $transport_type, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Transport Type',
        'required',
        'maxlength' => '100',
    ]) !!}
</div>
<label for="transport_type_id">Upload Images</label>


<input type="file" accept="image/*" class="form-control prof_box crop_upload_image" image_width="378" image_height="226"
    aspect_ratio_width="16" aspect_ratio_height="9" multiple upload_input_by_name="car_images[]" {!!isset($car->images)?'':"required"!!}
    onsuccess_function="show_image">

<div class="upload_images">

    @if (isset($car->images))
        @foreach ($car->images as $image_key => $image)
            <div class="car_images">
                <div onclick="remove_image(this)">X</div>

                <img src="{!! $image !!}">
                <input type="hidden" name="car_images_upload[]" value="{!! $image !!}">
            </div>
        @endforeach
    @endif

</div>

<div class="form-group">
    {!! Form::label('details', 'Details') !!}
    <div>
        {!! Form::text('details', null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Enter Details',
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
        function show_image(image) {
            var image_key = $(".car_images_upload").length;
            var img = `
            <div class="car_images">
                <div  onclick="remove_image(this)">X</div>
                <img src="` + image + `">
                <input type="hidden" name="car_images_upload[]" value="` + image + `">
            </div>
            `;
            $('.upload_images').append(img);
        }

        function remove_image(e) {
            $(e).parent().remove();
        }

        function validateForm() {
            return true;
        }
    </script>

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
@endsection
