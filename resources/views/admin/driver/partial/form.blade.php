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
    {{-- {!!dd($message->all())!!} --}}
    <div class="alert alert-danger">
        <ul>
            @foreach($message->all() as $error)
            
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <?php
    // $user = new \StdClass();
    $user = new App\Models\User;
    if(isset($driver)){
        $user = $driver->user_obj;
    }
?>
    <div class="form-group">
        {!! Form::label('name',' First Name') !!}
        <div>
            {!! Form::text('name', $user->name, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'First Name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <?php
    // $last_name = '';
    // if(isset($driver)){
    //     $last_name = $driver->user_name->last_name;
    // }
?>
    <div class="form-group">
        {!! Form::label('last_name',' Last Name') !!}
        <div>
            {!! Form::text('last_name', $user->last_name, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Last Name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('email','Email') !!}
        <div>
            {!! Form::text('email',  $user->email, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter email','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('driver_category','Drive Category') !!}
        <div>
            {!! Form::select('driver_category',  $driver_categories,null, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change','required','onclick'=>'show_commission_box(this)']) !!}
        </div>
    </div>
<?php 
    $show_category = '';
    if(isset($driver) && $driver->driver_category == 'out_source'){
        $show_category = 'display:none';
    }
?>
    <div class="form-group commission_salary" style="{!!$show_category!!}">
        {!! Form::label('commission','Salary') !!}
        <div>
            {!! Form::number('commision',  null, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change','min'=>'0',
            'placeholder'=>'Monthly Salary']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('address','Address') !!}
        <div>
            {!! Form::text('adderss',  $user->adderss, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Address']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('phone_no','Phone Number') !!}
        <div>
            {!! Form::text('phone_no',  $user->phone_no, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Phone Number','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('whatsapp_number','Whatsapp Number') !!}
        <div>
            {!! Form::text('whatsapp_number',  $user->whatsapp_number, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'+966123456***','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('iqama_number','Iqama Number') !!}
        <div>
            {!! Form::text('iqama_number',  $user->iqama_number, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Iqama Number','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('password','Password') !!}
        <div>
            {!! Form::password('password',  ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Password',
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
        function show_commission_box(e){
            if($(e).val()=='own'){
                $('.commission_salary').css('display','block');
            }
            else{
                $('.commission_salary').css('display','none');
            }
        }
        function validateForm() {
            return true;
        }
    
    </script>
    
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    
    @endsection
    
    