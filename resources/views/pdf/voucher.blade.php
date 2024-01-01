<?php
$order = $data['data'];
?>
<html>

<head>
    <style>
        .mainTicketBox {
            width: 90%;
            /* display: flex; */
            margin-top: 25px;
        }

        h3.padding-top {
            rotate: 270deg;
        }

        .main_rotate_box {
            rotate: -90deg;
            margin-right: -85px;
            margin-left: -87px;
        }

        .main_rotate_bo {
            rotate: -90deg;
        }

        .barcode {
            text-align: center;
            color: #154c79;
            margin-left: -150px;
            margin-top: -120px;
        }


        .side_heading {
            text-align: center;
            background: #154c79;
            color: white;
            font-size: 17px;
            letter-spacing: 5px;
            width: 112%;
            padding-top: 1px;
            margin-top: 28px;
            padding-bottom: 1px;
            border-bottom-right-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        img.img-response {
            margin-top: 5px;
            margin-bottom: -10px;
        }

        td {
            padding: 0px;
            margin: 0px;
        }

        .time-box-center {
            text-align: center;
            color: #154c79;
        }

        .pass {
            font-size: 15px;
            font-weight: 200;
            color: #154c79;
            padding-bottom: 9px;
        }

        .pass_detail {
            color: #154c79;
        }

        .Passenger_name {
            margin-bottom: 8px;
            margin-top: 4px;
        }

        .Passenger_name {
            margin-bottom: 8px;
            margin-top: 8px;
            color: #154c79;
            padding-bottom: 8px;
            border-bottom-style: dotted;
        }

        .text_center {
            text-align: center;
            color: #154c79;
            font-size: 14px;
        }

        td.table-on-line {
            border-right: dotted;
            border-left: dotted;
            color: #154c79;
            padding-right: 15px;
            padding-left: 15px;
        }

        .booking-area_text h3 {
            color: #154c79;
            margin-bottom: 10px;
        }

        .withaf {
            width: 55%;
            text-align: center;
            color: #154c79;
        }

        img.logo_img {
            width: 160px;
            margin-left: 25px;
            margin-right: 25px;
        }

        p.font_type {
            font-size: 11px;
            color: #154c79;
            margin-top: 30px;
        }

        .booking-area {
            margin-left: 10px;
        }

        .last_box {
            background: #154c79;
            color: white;
            padding-top: 25px;
            padding-left: 25px;
            padding-right: 20px;
            padding-bottom: 5px;
            border-radius: 20px 1px 1px 20px;
        }

        td.center_on {
            width: 48%;
        }

        .color_w {
            color: white;
            font-size: 14px;
            text-align: center;
        }

        .passengerName {
            font-size: 13px;
            font-weight: 100;
            border-bottom-style: dotted;
            margin-bottom: 10px;
            letter-spacing: 1px;
            width: 85%;
            margin-right: 61px;
        }

        .passengerName h5 {
            margin-bottom: 10px;
            margin-top: -7px;
        }

        tr.barcode_box_last_txt {
            font-size: 13px;
            color: white;
            letter-spacing: 2px;
        }

        td.lastes {
            width: 60%;
        }

        .display_ecd {
            color: #154c79;
        }

        tr.fnin {
            box-shadow: 0px 0px 20px -4px;
        }
    </style>
</head>

<body>

    <center>
        <div class="mainTicketBox">
            <table>
                <thead>
                    <tr class="fnin">
                        <td>
                            <div class="main_rotate_box">
                                <div class="side_heading">

                                    <p><b> UMRAH TRANSPORT<br>
                                            DEMAS</b></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="main_rotate_bo">

                                <div class="barcode">
                                    <img src="{{ asset('images/Screenshot 2023.png') }}" class="img-response">
                                    <p><b>BOOKING NO : 00050-25</b></p>
                                </div>
                            </div>
                        </td>
                        <td class="table-on-line">
                            <div class="ditial_area">

                                <div class="Passenger_name">
                                    <span class="pass">Passenger</span>
                                    <br>
                                    <span class="pass_detail"><b>MR. ATIF BAQA</b></span>

                                </div>
                                <div class="Passenger_name">
                                    <table class="time-box-center">
                                        <tr>
                                            <td>Time</td>
                                            <td>Total passenger</td>
                                            <td>Flight</td>
                                        </tr>
                                        <tr>
                                            <th>05:20 AM</th>
                                            <th>11</th>
                                            <th>SAUDI AIRLINE
                                            </th>
                                        </tr>
                                    </table>

                                </div>
                                <div class="Passenger_name">
                                    <table>
                                        <tr class="text_center">
                                            <td class="center_on">Date</td>
                                            <td>From</td>
                                        </tr>
                                        <tr class="text_center">
                                            <th>03.11.2023</th>
                                            <th>KARACHI, AIRPORT</th>
                                        </tr>
                                    </table>

                                </div>
                                <div class="Passenger_name">
                                    <span class="text_center">To</span>
                                    <br>
                                    <span class="text_center"><b>JEDDAH, AIRPORT</b></span>

                                </div>
                                <div class="Passenger_na">

                                    <div class="last_row">
                                        <table>
                                            <tr>
                                                <td class="withaf">Vehicle</td>
                                                <td class="display_ecd">
                                                    <div class="last_row_text">Passenger Capacity</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="withaf">HIACE</th>
                                                <th class="display_ecd">
                                                    <div class="last_row_text">11</div>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </td>
                        <td>
                            <div class="booking-area">
                                <div class="booking-area_text">
                                    <h3>BOOKING <br>CONFIRM</h3>
                                </div>
                                <img src="{{ asset('images/demas-logo.png') }}" class="logo_img">
                                <p class="font_type">
                                    NOTE : PLEASE INFORM<br>
                                    IMMEDIATELY IF ANY CHANGE<br>
                                    OCCURS IN FLIGHT SCHEDULE.<br>
                                </p>
                            </div>
                        </td>
                        <td>
                            <div class="last_box">
                                <div class="right-side-box">
                                    <div class="">
                                        <img src="{{ asset('images/Screenshot 2023-12-28 183500.png') }}" class="">
                                    </div>
                                    <div class="passengerName">
                                        <p>Passenger</p>
                                        <h5>MR. ATIF BAQA</h5>
                                    </div>
                                    <div class="passengerName">
                                        <table>
                                            <tr class="color_w">
                                                <td class="">Boarding Time</td>
                                                <td>Terminal</td>
                                                <td>Flight</td>
                                            </tr>
                                            <tr class="color_w">
                                                <td>-</td>
                                                <td>-</td>
                                                <td> SV-705</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="barcode_box_last">

                                        <table>
                                            <tr class="barcode_box_last_txt">
                                                <td>From </td>
                                                <td>To</td>
                                            </tr>
                                            <tr class="barcode_box_last_txt">
                                                <td class="lastes">KARACHI,<br>
                                                    AIRPORT</td>
                                                <td>JEDDAH,<br>
                                                    AIRPORT</td>
                                            </tr>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                </thead>
            </table>
        </div>
    </center>

</body>

</html>