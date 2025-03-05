<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; font-size: 20px; font-weight: bold; }
        .details { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Invoice #{{ $invoice->invoice_number }}</div>
        <div class="details">
            <p><strong>Email:</strong> {{ $invoice->customer_email }}</p>
            <p><strong>Amount:</strong> ${{ $invoice->amount }}</p>
            <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        </div>
    </div>
</body>
</html>
