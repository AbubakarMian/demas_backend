<?php 
    dd($order, $order->order_details ,$order->order_details[0]->transport_type->name ,$order->order_details[0]);

    $order = $data['data'];

?>

<!DOCTYPE html>



<html>

<head>
    <style>
        .main_box {
            width: 40%;
        }

        .logo-img {
            width: 55%;
            margin-top: 95px;
        }

        .fgegh {
            margin-top: 70px;
        }

        .onLine_img {
            width: 100%;
            padding-left: 100px;
            text-align: end;
        }

        .onLine {
            display: flex;
            justify-content: center;
        }

        .onLine_text {
            width: 100%;
            text-align: end;
            display: flex;
            justify-content: space-evenly;
            align-content: flex-end;
            flex-wrap: wrap;
            flex-direction: column-reverse;
        }

        .ontext {
            width: 100%;
            text-align: left;
        }

        .textColor {
            color: #f39b1c;
        }

        .ontext-2 {
            color: #f39b1c;
        }

        .onLine_txt {
            width: 100%;
            margin-top: -20px;
            text-align: end;
        }

        th.detail-col {
            width: 60%;
            padding-bottom: 15px;
            padding-top: 15px;
        }

        th.price-col {
            width: 20%;
            padding-bottom: 15px;
            padding-top: 15px;
        }

        th.date-col {
            width: 20%;
            padding-bottom: 15px;
            padding-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid gray;
            color: gray;
            height: 40px;
            padding-left: 10px;
        }

        .tableHeadColor {
            background: #021b3b;
            color: white;
            font-size: 20px;
            font-weight: 100;
        }

        .bookingDate {
            font-size: 15px;
            color: gray;
            text-align: end;
            padding-right: 15px;
        }

        .bookingHeading h1 {
            color: #004aad;
            margin-top: 0px;
            margin-bottom: -5px;
        }

        .total_area {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .total_txt {
            width: 30%;
            text-align: left;
            background: #021b3b;
            color: white;
            padding-left: 16px;
        }

        .total_price {
            background: #021b3b;
            color: white;
            padding-right: 10px;
        }

        .note_area h3 {
            text-align: left;
        }

        .note_area p {
            text-align: left;
            color: gray;
            margin-top: -10px;
            margin-left: 40px;
        }

        .contact_box p {
            text-align: left;
            margin-top: -10px;
            color: gray;
            font-size: 18px;
        }

        .gnrisno {
            width: 100px;
        }

        .top-off {
            margin-top: -40px;
        }
    </style>
</head>

<body>
    <center>

        <div class="main_box">

            <div class="onLine">
                <div class="gnrisno">
                    <div class="leftFtrImg">
                        <img src="./Screenshot32_cleanup.png">
                    </div>
                </div>
                <div class="onLine_img">


                    <img src="./demas-logo.png" class="logo-img">
                </div>
                <div class="onLine_text fgegh">
                    <h3>BKN NO ::<br>
                        BKN-{!!$order->order_id!!}</h3>
                </div>
            </div>

            <div class="onLine">
                <div class="ontext">
                    <h3>To:<br>
                        <span class="ontext-2">{!!$order->customer_name!!}</span>
                    </h3>

                </div>
                <div class="onLine_text">
                    <h3>From:<br>
                        <span class="textColor">UMRAH TRANSPORT</span><br>
                        <span class="textColor">DEMAS</span>
                    </h3>
                </div>
            </div>

            <div class="onLine">
                <div class="onLine_txt">
                    <h3>VEHICLE DETAIL :<br>
                        <span class="textColor">UMRAH TRANSPORT</span><br>
                        <span class="textColor"> {!!$order->order_details[0]->transport_type->name!!}({!!$order->order_details[0]->transport_type->seats!!} PAX)</span>
                    </h3>
                </div>
            </div>
            <div class="bookingHeading">
                <h1>BOOKING {!!$order->status!!}</h1>
                <div class="bookingDate">
                    <p>DATE : {!!$order->created_at!!}
                    </p>
                </div>
            </div>
            <div class="">
                <table>
                    <thead>
                        <tr class="tableHeadColor">
                            <th class="detail-col">Description</th>
                            <th class="date-col">DATE</th>
                            <th class="price-col">PRICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->order_details as $key=> $order_item)
                        <tr>
                            <td>{!!$order_item->pickup_location->name.($order_item->pick_extrainfo?'('.$order_item->pick_extrainfo.')':'') !!}</td>
                            <td>{!!date('Y-m-d', $order_item->pick_up_date_time)!!}</td>
                            {{-- <td>{!!$order_item->final_price!!}</td> --}}

                            {{-- <td>Jeddah Airport To Makkah Hotel</td>
                            <td>03-11-2023</td> --}}
                            <td></td>
                        </tr>
                        {{-- <tr>
                            <td>Makkah Hotel To Madina Hotel</td>
                            <td>-</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Madina Hotel To Jeddah Airport</td>
                            <td>12-11-2023</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr> --}}
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="total_area">
                <div class="total_txt">
                    <p>SUB TOTAL =</p>
                </div>
                <div class="total_price">
                    <p>{!!$order_item->final_price!!}/=</p>
                </div>
            </div>

            <div class="note_area">
                <h3>Note:</h3>
                <p>
                    <u> ALL THE PAYMENT MUST BE PAID IN SAUDI RIYAL ONLY <br>
                        IF THERE IS ANY DELAY IN FLIGHT PLEASE INFORM<br>
                        IMMEDIATELY TO PROVIDED NUMBER.
                    </u>
                </p>
                <img src="./Screenshot_2023-12-28_003656_cleanup-removebg-preview.png" class="top-off">
            </div>
            <div class="contact_box">
                <p>Call/Whatsapp: +966-5065-94634</p>
                <p>Email: mailto:info@umrahtransportdemas.com</p>
                <p>Website : https://www.demasalarabia.com</p>
            </div>

        </div>

    </center>
</body>

</html>
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
