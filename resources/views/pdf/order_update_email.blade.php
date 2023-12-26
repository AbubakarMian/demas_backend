<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
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
            <h1>Order Invoice</h1>
        </div>
        
</body>
</html>
