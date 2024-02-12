<?php
$order = $data['data'];
$pax_vale = '';

if (isset($order->order_details[0])) {
    $pax_value = '<span class="textColor">' . $order->order_details[0]->transport_type->name . ' ' . $order->order_details[0]->total_passengers . '  PAX</span>';
}

// dd($order, $order->order_details, 'nam', $order?->order_details[0]?->transport?->name, $order->order_details[0]);

?>

<!DOCTYPE html>



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
            left: 430px;
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
            float: right;
        }

        .leftFtrImg img {
            width: 550%;
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
            text-align: right;
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

        .sadasdsa h3 {
            float: right;
        }

        .dsdds {
            position: relative;

        }

        .dsdds h3 {
            right: 0px;
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
            <div class="sdadsa">
                <div class="onLine_img">
                    <img src="{{ asset('images/demas-logo.png') }}" class="logo-img">
                </div>
                <div class="onLine_text sadasdsa  hed_clr fgegh">
                    <h3>BKN NO ::<br>BKN-{!! $order->order_id !!}</h3>
                    <br>
                    <br>
                    <br>

                    {{-- <h3>
                        From:<br />
                        <span class="textColor">UMRAH TRANSPORT</span><br />
                        <span class="textColor">DEMAS</span><br>
                        <span class="textColor">{!! $order->order_details[0]->transport_type->name !!}({!! $order->order_details[0]->transport_type->seats !!} PAX)</span>
    
                    </h3> --}}
                </div>
            </div>
        </div>

        <div class="onLine">
            <div class="ontext">
                <h3>To:<br><span class="ontext-2">{!! $order->customer_name !!}</span></h3>
            </div>
            <div class="onLine_text sadasdsa hed_clr  dsdds">
                <h3>
                    From:
                    <br />
                    <span class="textColor">UMRAH TRANSPORT</span><br />
                    <span class="textColor">DEMAS</span><br>
                    <?php
                    ?>

                    {{-- <span class="textColor">{!! $pax_vale !!}</span> --}}
                    <span class="textColor"> {!!$order->order_details[0]->transport_type->name . ' (' . $order->order_details[0]->total_passengers .'PAX)'!!} </span>

                </h3>
            </div>
        </div>

        <div class="bookingHeading">
            <center>
                {{-- <h1>BOOKING {!! strtoupper($order->status) !!}</h1> --}}
                <h1>BOOKING</h1>
            </center>

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
                        {{-- <th>Driver Name</th>
                        <th>Driver Whatsapp Number</th> --}}
                        <th>Pickup Date</th>
                        <th>Pickup Time</th>
                        <th>Adult PAX</th>
                        <th>Infant PAX</th>
                        <th>Total PAX</th>
                        <th>Unit Price</th>
                        <th>Status</th>
                        @if ($order->show_price_in_user_invoice)
                            <th>Payment</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <@php
                        $total_price = 0;

                    @endphp @foreach ($order->active_order_details as $key => $order_item)
                       <tr>
                            <td>{!! $order_item->order_id !!}</td>
                            <td>{!! $order_item->pickup_location->name .
                                ($order_item->pick_extrainfo ? '(' . $order_item->pick_extrainfo . ')' : '') !!}</td>
                            <td>{!! $order_item->dropoff_location->name .
                                ($order_item->dropoff_extrainfo ? '(' . $order_item->dropoff_extrainfo . ')' : '') !!}</td>
                            <td>{!! $order_item->transport?->name !!}</td>
                            {{-- <td>{!! $order_item->driver_user?->name ?? '' !!}</td>
                            <td>{!! $order_item->driver_user?->whatsapp_number ?? '' !!}</td> --}}
                            <td>{!! date('d-m-Y', $order_item->pick_up_date_time) !!}</td>
                            <td>{!! date('H:i:s', $order_item->pick_up_date_time) !!}</td>
                            <td>{!! $order_item->adult_passengers !!}</td>
                            <td>{!! $order_item->infant_passengers !!}</td>
                            <td>{!! $order_item->total_passengers !!}</td>
                            <td>{!! $order_item->final_price !!}</td>
                            <td>{!! $order_item->status !!}</td>
                            <@php
                                $total_price += $order_item->final_price;
                            @endphp @if ($order->show_price_in_user_invoice)
                                <td>{!! $order_item->user_payment_status !!}</td>
                                @endif
                                </tr>
                                @endforeach
                </tbody>
            </table>
        </div>
        @if ($order->show_price_in_user_invoice)
            <div class="total_area">
                <div class="total_txt">
                    <p>SUB TOTAL = <span class="">{!! $order_item->final_price !!}/=</span></p>
                </div>
                <!-- <div class="total_price">
          <p></p>
        </div> -->
            </div>
        @endif
        <div class="total_area">
            <div class="total_txt">
                <p>GRAND TOTAL = <span class="">{!! $total_price !!}/=</span></p>
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
