<!DOCTYPE html>
<html>
<head>
    <title>Scanned Products PDF</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; }
        img { width: 50px; height: 50px; object-fit: cover; }
    </style>
</head>
<body>
    <h2>Scanned Products</h2>
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
                    <td><img src="{{ public_path('storage/images/' . $scan->db_img) }}" alt="{{ $scan->product_name }}"></td>
                    <td>{{ $scan->product_name }}</td>
                    <td>{{ $scan->product_description }}</td>
                    <td>{{ $scan->product_quantity }}</td>
                    <td>{{ number_format($scan->product_price, 2) }}</td>
                    <td>{{ $scan->barcode_id }}</td>
                    <td>{{ $scan->supplier_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
