
{{-- {!!dd($data)!!} --}}
<?php

// if (isset($user)) {
//     dd('asdsda',$user);
// }
// $email_details['data'] = $user;
dd($data); // Add this line for debugging

?>

<!DOCTYPE html>
<html>
<head>
    <title>Demas</title>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <img src="{{ asset('images/logo.png') }}">
            {{-- <img src="'. public_path() .'/images/logo.png"> --}}
            <h1>OTP</h1>
        </div>
        <div class="invoice-info">
            <p> your otp is<strong> {!!$$data->otp!!}</strong></p>
            {{-- <p><strong>Due Date:</strong> November 21, 2023</p> --}}
        </div>
         
        {{-- <div class="invoice-total">
            <p><strong>Total:</strong> $190.00</p>
        </div> --}}
    </div>

</body>
</html>