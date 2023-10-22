<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            background: #f9f9f9;
        }
        .invoice-header {
            text-align: center;
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
            <h1>Invoice</h1>
        </div>
        <div class="invoice-info">
            <p><strong>Invoice Number:</strong> INV-2023-001</p>
            <p><strong>Invoice Date:</strong> October 21, 2023</p>
            <p><strong>Due Date:</strong> November 21, 2023</p>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product A</td>
                    <td>2</td>
                    <td>$50.00</td>
                    <td>$100.00</td>
                </tr>
                <tr>
                    <td>Product B</td>
                    <td>3</td>
                    <td>$30.00</td>
                    <td>$90.00</td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-total">
            <p><strong>Total:</strong> $190.00</p>
        </div>
    </div>
</body>
</html>
