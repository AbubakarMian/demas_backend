<!DOCTYPE html>
<?php 
    $order = $data['order'];
?>
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
            <h1>Booking Information</h1>
        </div>
        <div class="invoice-info">
            <p><strong>Booking ID:</strong>{!!$order->order_id!!}</p>
            <p><strong>Invoice Date:</strong>{!!$order->created_at!!}</p>
            {{-- <p><strong>Due Date:</strong> November 21, 2023</p> --}}
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Guest Whatsapp</th>
                    <th>Guest Contact</th>
                    {{-- <th>Booking Status</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{!!$order->customer_name!!}</td>
                    <td>{!!$order->customer_whatsapp_number!!} </td>
                    <td>{!!$order->customer_number!!}</td>
                    {{-- <td>{!!$order->status!!}</td> --}}
                </tr>
              
            </tbody>
        </table>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Vehicle</th>
                    <th>Driver Name</th>
                    <th>Driver Whatsapp Number</th>
                    <th>Pickup Date</th>
                    <th>Pickup Time</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->order_details as $key=> $order_item)
                <tr> 
                    <td>{!!$order_item->order_id!!}</td>
                    <td>{!!$order_item->pickup_location->name.($order_item->pick_extrainfo?'('.$order_item->pick_extrainfo.')':'') !!}</td>
                    <td>{!!$order_item->dropoff_location->name.($order_item->dropoff_extrainfo?'('.$order_item->dropoff_extrainfo.')':'') !!}</td>
                    <td>{!!$order_item->transport_type->name!!}</td>
                    <td>{!!$order_item->driver_user?->name??''!!}</td>
                    <td>{!!$order_item->driver_user?->whatsapp_number??''!!}</td>
                    <td>{!!date('Y-m-d', $order_item->pick_up_date_time)!!}</td>
                    <td>{!!date('H:i:s', $order_item->pick_up_date_time)!!}</td>
                    <td>{!!$order_item->status!!}</td>
                    <td>{!!$order_item->user_payment_status!!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>    
        {{-- <div class="invoice-total">
            <p><strong>Total:</strong> $190.00</p>
        </div> --}}
    </div>
</body>
</html>
