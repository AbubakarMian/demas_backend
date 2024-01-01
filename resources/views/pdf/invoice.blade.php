<?php
// dd($order, $order->order_details ,$order->order_details[0]->transport_type->name ,$order->order_details[0]);

$order = $data['data'];
?>

<!DOCTYPE html>



<html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main_box {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .leftFtrImg img {
            width: 550%;
        }

        .logo-img {
            width: 55%;
            margin-top: 20px;
        }

        .onLine {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .gnrisno img {
            width: 100px;
        }

        .onLine_text {
            text-align: right;
        }

        .onLine_txt {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .tableHeadColor {
            background: #021b3b;
            color: white;
        }

        .bookingHeading {
            margin-top: 20px;
            text-align: center;
        }

        .total_area {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .total_txt,
        .total_price {
            background: #021b3b;
            color: white;
            padding: 10px;
            margin-left: 10px;
        }

        .note_area {
            margin-top: 20px;
        }

        .note_area h3 {
            text-align: left;
        }

        .contact_box {
            margin-top: 20px;
        }

        .contact_box p {
            font-size: 18px;
            margin: 5px 0;
        }

        .top-off {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="main_box">
        <div class="onLine">
            <div class="gnrisno">
                <img src="{{ asset('images/Screenshot32_cleanup.png') }}">
            </div>
            <div class="onLine_img">
                <img src="{{ asset('images/demas-logo.png') }}" class="logo-img">
            </div>
            <div class="onLine_text fgegh">
                <h3>BKN NO ::<br>BKN-{!! $order->order_id !!}</h3>
            </div>
        </div>

        <div class="onLine">
            <div class="ontext">
                <h3>To:<br><span class="ontext-2">{!! $order->customer_name !!}</span></h3>
            </div>
            <div class="onLine_text">
                <h3>From:<br><span class="textColor">UMRAH TRANSPORT</span><br><span class="textColor">DEMAS</span></h3>
            </div>
        </div>

        <div class="onLine">
            <div class="onLine_txt">
                <h3>VEHICLE DETAIL :<br><span class="textColor">UMRAH TRANSPORT</span><br>
                    <span class="textColor">{!! $order->order_details[0]->transport_type->name !!}({!! $order->order_details[0]->transport_type->seats !!} PAX)</span>
                </h3>
            </div>
        </div>

        <div class="bookingHeading">
            <h1>BOOKING {!! $order->status !!}</h1>
            <p class="bookingDate">DATE : {!! $order->created_at !!}</p>
        </div>

        <div class="">
            <table>
                <thead>
                    <tr class="tableHeadColor">
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
                    @foreach ($order->order_details as $key => $order_item)
                        <tr>
                            <td>{!! $order_item->order_id !!}</td>
                            <td>{!! $order_item->pickup_location->name .
                                ($order_item->pick_extrainfo ? '(' . $order_item->pick_extrainfo . ')' : '') !!}</td>
                            <td>{!! $order_item->dropoff_location->name .
                                ($order_item->dropoff_extrainfo ? '(' . $order_item->dropoff_extrainfo . ')' : '') !!}</td>
                            <td>{!! $order_item->transport_type->name !!}</td>
                            <td>{!! $order_item->driver_user?->name ?? '' !!}</td>
                            <td>{!! $order_item->driver_user?->whatsapp_number ?? '' !!}</td>
                            <td>{!! date('Y-m-d', $order_item->pick_up_date_time) !!}</td>
                            <td>{!! date('H:i:s', $order_item->pick_up_date_time) !!}</td>
                            <td>{!! $order_item->status !!}</td>
                            <td>{!! $order_item->user_payment_status !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total_area">
            <div class="total_txt">
                <p>SUB TOTAL =</p>
            </div>
            <div class="total_price">
                <p>{!! $order_item->final_price !!}/=</p>
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
            <img src="{{ asset('images/Screenshot_2023-12-28_003656_cleanup-removebg-preview.png') }}" class="top-off"
                alt="">
        </div>

        <div class="contact_box">
            <p>Call/Whatsapp: +966-5065-94634</p>
            <p>Email: info@umrahtransportdemas.com</p>
            <p>Website: https://www.demasalarabia.com</p>
        </div>
    </div>
</body>

</html>
--}}
<html>

<head>
    <style>
        .main_box {
            /* width: 40%; */
            position: relative;
            top: 0;
            left: 0;
        }

        .logo-img {
            width: 30%;
            /* margin-top: 95px; */
        }

        .fgegh {
            margin-top: 70px;
        }

        .onLine_img {
            width: 83%;
            /* padding-left: 100px; */
            text-align: end;
            position: absolute;
            top: 100px;
            left: -250px;
        }

        .total_txt span {
            float: right;
            padding-right: 25px;
        }

        .onLine {
            /* display: flex;
            justify-content: center; */
        }

        .onLine_text {
            width: 100%;
            text-align: end;
            /* display: flex; */

        }

        .hed_clr {
            color: #7c7474;
            font-weight: bold;
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
            /* margin-top: -20px; */
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
            /* display: flex; */
            justify-content: flex-end;
            margin-top: 25px;
        }

        .total_txt {
            width: 30%;
            text-align: left;
            background: #021b3b;
            color: white;
            padding-left: 16px;
            float: right;
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
            font-weight: bold;
            margin-left: 35px;
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
    <!-- <center> -->
    <div class="main_box">
        <div class="onLine">
            <div class="gnrisno">
                <div class="leftFtrImg">
                    <img src="{{ asset('images/Screenshot32_cleanup.png') }}">
                </div>
            </div>
            <div class="onLine_img">
                <img src="{{ asset('images/demas-logo.png') }}" class="logo-img">
            </div>
            <div class="onLine_text  hed_clr fgegh">
                <h3>BKN NO ::<br>BKN-{!! $order->order_id !!}</h3>
            </div>
        </div>

        <div class="onLine">
            <div class="ontext">
                <h3>To:<br><span class="ontext-2">{!! $order->customer_name !!}</span></h3>
            </div>
            <div class="onLine_text hed_clr">
                <h3>
                    From:<br />
                    <span class="textColor">UMRAH TRANSPORT</span><br />
                    <span class="textColor">DEMAS</span>
                </h3>
            </div>
        </div>

        <div class="onLine">
            <div class="onLine_txt hed_clr">
                <h3>
                    <span class="textColor">{!! $order->order_details[0]->transport_type->name !!}({!! $order->order_details[0]->transport_type->seats !!} PAX)</span>

                </h3>
            </div>
        </div>
        <div class="bookingHeading">
            <h1>BOOKING {!! $order->status !!}</h1>
            <div class="bookingDate">
                <p class="bookingDate">DATE : {!! $order->created_at !!}</p>
            </div>
        </div>
        <div class="">
            <table>
                <thead>
                    <tr class="tableHeadColor">
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
                    @foreach ($order->order_details as $key => $order_item)
                        <tr>
                            <td>{!! $order_item->order_id !!}</td>
                            <td>{!! $order_item->pickup_location->name .
                                ($order_item->pick_extrainfo ? '(' . $order_item->pick_extrainfo . ')' : '') !!}</td>
                            <td>{!! $order_item->dropoff_location->name .
                                ($order_item->dropoff_extrainfo ? '(' . $order_item->dropoff_extrainfo . ')' : '') !!}</td>
                            <td>{!! $order_item->transport_type->name !!}</td>
                            <td>{!! $order_item->driver_user?->name ?? '' !!}</td>
                            <td>{!! $order_item->driver_user?->whatsapp_number ?? '' !!}</td>
                            <td>{!! date('Y-m-d', $order_item->pick_up_date_time) !!}</td>
                            <td>{!! date('H:i:s', $order_item->pick_up_date_time) !!}</td>
                            <td>{!! $order_item->status !!}</td>
                            <td>{!! $order_item->user_payment_status !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total_area">
            <div class="total_txt">
                <p>SUB TOTAL = <span class="">{!! $order_item->final_price !!}/=</span></p>
            </div>
            <!-- <div class="total_price">
          <p></p>
        </div> -->
        </div>

        <div class="note_area">
            <h3>Note:</h3>
            <p>
                <u>
                    ALL THE PAYMENT MUST BE PAID IN SAUDI RIYAL ONLY <br />
                    IF THERE IS ANY DELAY IN FLIGHT PLEASE INFORM<br />
                    IMMEDIATELY TO PROVIDED NUMBER.
                </u>
            </p>
            <img src="{{ asset('images/Screenshot_2023-12-28_003656_cleanup-removebg-preview.png') }}" class="top-off"
                alt="">
        </div>
        <div class="contact_box">
            <p>Call/Whatsapp: +966-5065-94634</p>
            <p>Email: Info@umrahtransportdemas.com</p>
            <p>Website : www.demasalarabia.com</p>
        </div>
    </div>
    <!-- </center> -->
</body>

</html>
