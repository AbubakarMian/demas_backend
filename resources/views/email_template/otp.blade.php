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
    <title>Demas OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            /* background: #f9f9f9; */
        }
        .invoice-header {
            text-align: center;
        }
        .invoice-header img {
            max-width: 200px; /* Set the maximum width for the logo */
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-info {
            margin-top: 20px;
        }
        .invoice-info p {
            margin: 5px 0;
        }
        .invoice-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-table th {
            background: #f2f2f2;
        }
        .invoice-total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
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
