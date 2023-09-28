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
    <?php
    $name = '';
    if(isset($driver)){
        $name = $driver->user_name->name;
    }
?>
    <div class="form-group">
        {!! Form::label('name',' First Name') !!}
        <div>
            {!! Form::text('name', $name, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'First Name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <?php
    $last_name = '';
    if(isset($driver)){
        $last_name = $driver->user_name->last_name;
    }
?>
    <div class="form-group">
        {!! Form::label('last_name',' Last Name') !!}
        <div>
            {!! Form::text('last_name', $last_name, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Last Name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    
    <?php
    $email = '';
    if(isset($driver)){
        $email = $driver->user_name->email;
    }
?>
    <div class="form-group">
        {!! Form::label('email','Email') !!}
        <div>
            {!! Form::text('email',  $email, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter email','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <?php
    $city = '';
    if(isset($driver)){
        $city = $driver->user_name->city;
    }
?>
    <div class="form-group">
        {!! Form::label('address','Address') !!}
        <div>
            {!! Form::text('address',  $city, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Address','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
   
    <?php
        $number = '';
        if(isset($user)){
            $number = $user->phone_no;
        }
    ?>
    <div class="form-group">
        {!! Form::label('phone_no','Phone Number') !!}
        <div>
            {!! Form::text('phone_no',  $number, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Phone Number','required',
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
        function validateForm() {
            return true;
        }
    
    </script>
    
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    
    @endsection
    
    