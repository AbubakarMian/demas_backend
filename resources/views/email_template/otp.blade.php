<!DOCTYPE html>
<html>

<head>
    <style>
        .outer_bx {
            border: solid 1px #b66f09;
            padding: 10px;
        }

        .lgoo_area img {
            width: 100px;
        }

        .lin_area {
            display: flex;
            justify-content: center;
        }

        span.line {
            border-top: solid 3px white;
            width: 118px;
            margin-top: 42px;
        }

        .img_area img {
            width: 38px;
            padding: 20px;
        }

        .clr_area {
            background-color: #b66f09;
            padding: 25px 0px;
        }

        span.line {
            border-top: solid 3px white;
            width: 118px;
            margin-top: 42px;
        }

        .txt_area h1 {
            color: white;
            padding-bottom: 10px;
        }

        .txt_area h2 {
            font-weight: 100;
            color: white;
        }

        .flx {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        span.bx {
            border: solid 1px #edb169;
            padding: 8px;
            border-radius: 5px;
            margin: 10px;
            box-shadow: 0px 0px 3px 0px #c46b00;
            color: #7e7272;
            font-size: 20px;
        }

        button.cp_btn {
            border: solid 1px #edb169;
            padding: 8px;
            border-radius: 5px;
            /* margin: 10px; */
            box-shadow: 0px 0px 3px 0px #c46b00;
            background-color: #c46b00;
            color: white;
        }

        .icn_ft {
            background: #c46b00;
            height: 70px;
            margin-top: 15px;
        }

        .icn_ft img {
            width: 27px;
            padding: 20px;
        }

        .ln {
            border-right: solid 2px white;
            height: 40px;
            margin-top: 15px;
        }

        .message_area h5 {
            font-size: 15px;
        }

        .message_area {
            color: gray;
        }
    </style>
</head>

<body>
    <center>
        <div class="outer_bx">
            <div class="lgoo_area"><img src="{{ asset('images/12.png') }}" /></div>
            <div class="clr_area">
                <div class="lin_area">

                </div>
                <div class="txt_area">
                    <h2>Thank You For Choosing Demas</h2>
                    <h1>Verify That its "YOU" to get started</h1>
                </div>
            </div>
            <div class="message_area">
                <h5>Hello There.</h5>
                <p>Please use the following One Time Password (OTP)</p>
                <br />
                <div class="fssssslx">
                    <div id="textToCopy" class="otp_area">
                        <span class="bx"> {!! $data->otp !!}</span>

                    </div>
                </div>
                <br /><br />
                <div class="sadas">
                    Your security is our top priority. We want to remind you that the
                    One-Time Password <br />
                    (OTP) you receive is a confidential and sensitive piece of
                    information.<br />

                    Do not share your OTP with anyone, including support personnel.<br />
                    The OTP is generated exclusively for your use and should remain
                    confidential.<br />

                    If you have any concerns or questions, please contact our support
                    team immediately.<br />
                    Thank you for your cooperation in maintaining the security of your
                    account.
                    <br />
                    Best regards,
                </div>
            </div>
            <div class="footer">
                <div class="icn_ft lin_area">
                    <div class="web">
                        <a href="https://app.demastransport.com/"><img src="{{ asset('images/internet.png') }}" /></a>
                    </div>
                    <div class="ln"></div>
                    <div class="face">
                        <a href="https://www.facebook.com/profile.php?id=61550522987397"><img
                                src="{{ asset('images/facebook.png') }}" /></a>
                    </div>
                </div>
            </div>
        </div>
    </center>
</body>

</html>
