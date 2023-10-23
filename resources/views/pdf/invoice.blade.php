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
            {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo"> --}}
            <h1>Deemas Invoice</h1>
        </div>
        <div class="invoice-info">
            <p><strong>Booking ID:</strong>2023-001</p>
            <p><strong>Invoice Date:</strong> October 21, 2023</p>
            <p><strong>Due Date:</strong> November 21, 2023</p>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Guest Whatsapp</th>
                    <th>Guest Contact</th>
                    <th>Booking Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Abu bakar</td>
                    <td>123-456-7890 </td>
                    <td>123-456-7890</td>
                    <td>Confirmed</td>
                </tr>
              
            </tbody>
        </table>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Vehicle</th>
                    <th>Driver Name</th>
                    <th>Driver Whatsapp Number</th>
                    <th>Pickup Date</th>
                    <th>Pickup Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Madinah Airport
                        (30APR PK 743
                        KHI MED2200 0
                        030)</td>
                    <td>Madina Hotel
                        (Emmar Royal
                        Madinah) </td>
                    <td>Sedan</td>
                    <td>Ali</td>
                    <td>123-456-7890</td>
                    <td>16-06-2023</td>
                    <td>08:30 AM</td>
                </tr>
                <tr>
                    <td>Madinah Airport
                        (30APR PK 743
                        KHI MED2200 0
                        030)</td>
                    <td>Madina Hotel
                        (Emmar Royal
                        Madinah) </td>
                    <td>Sedan</td>
                    <td>Ali</td>
                    <td>123-456-7890</td>
                    <td>16-06-2023</td>
                    <td>08:30 AM</td>
                </tr>
              
            </tbody>
        </table>    
        {{-- <div class="invoice-total">
            <p><strong>Total:</strong> $190.00</p>
        </div> --}}
    </div>
</body>
</html>
