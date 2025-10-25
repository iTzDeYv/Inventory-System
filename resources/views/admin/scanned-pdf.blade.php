<!DOCTYPE html>
<html>
<head>
    <title>Scanned Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        img {
            height: 50px;
            width: 50px;
            object-fit: cover;
        }
        .totals {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Scanned Product List</h2>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Barcode</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scans as $scan)
                <tr>
                    <td><img src="{{ public_path('db_img/' . $scan->product_image) }}"></td>
                    <td>{{ $scan->product_name }}</td>
                    <td>{{ $scan->product_description ?? '-' }}</td>
                    <td>{{ $scan->quantity }}</td>
                    <td>{{ number_format($scan->product_price, 2) }}</td>
                    <td>{{ $scan->barcode_id }}</td>
                    <td>{{ $scan->supplier_name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Total Quantity: {{ $totalQty }}</p>
        <p>Total Value: ${{ number_format($totalValue, 2) }}</p>
    </div>
</body>
</html>
