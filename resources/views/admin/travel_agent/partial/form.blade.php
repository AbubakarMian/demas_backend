{{-- {!!dd($travel_agent->user_obj)!!} --}}

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
            @foreach($message as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <?php
    $name = '';
    if(isset($travel_agent)){
        $name = $travel_agent->user_obj->name;
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
    if(isset($travel_agent)){
        $last_name = $travel_agent->user_obj->last_name;
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
    if(isset($travel_agent)){
        $email = $travel_agent->user_obj->email;
    }
?>
    <div class="form-group">
        {!! Form::label('email','Email') !!}
        <div>
            {!! Form::email('email',  $email, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter email','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <?php
    $adderss = '';
    if(isset($travel_agent)){
        $adderss = $travel_agent->user_obj->adderss;
    }
?>
    <div class="form-group">
        {!! Form::label('address','Address') !!}
        <div>
            {!! Form::text('adderss',  $adderss, ['class' => 'form-control',
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
            {!! Form::number('phone_no',  $number, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Phone Number','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
  
    <?php
        $whatsapp_number = '';
        if(isset($user)){
            $whatsapp_number = $user->whatsapp_number;
        }
    ?>
    <div class="form-group">
        {!! Form::label('whatsapp_number','Whatsapp Number') !!}
        <div>
            {!! Form::number('whatsapp_number',  $whatsapp_number, ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Whatsapp Number','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
   
    <div class="form-group">
        {!! Form::label('license_num','License Num') !!}
        <div>
            {!! Form::text('license_num',null,  ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter License Number','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('company_name','Company Name') !!}
        <div>
            {!! Form::text('company_name',null,  ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Company name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('country','Country') !!}
        <div>
            {!! Form::text('country',null,  ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter Country name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('city','City') !!}
        <div>
            {!! Form::text('city',null,  ['class' => 'form-control',
            'data-parsley-required'=>'true',
            'data-parsley-trigger'=>'change',
            'placeholder'=>'Enter City name','required',
            'maxlength'=>"100"]) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="sale_agent">Sale Agents</label>
        {!! Form::select('user_sale_agent_id', $user_sale_agents, null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder'=>'Select Sale Agent',
            'maxlength' => '100',
        ]) !!}
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
    
    