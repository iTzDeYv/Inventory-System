<x-app-layout>
    @php
        $totalQuantity = $products->sum('product_quantity');
        $totalValue = $products->sum(fn($p) => $p->product_price * $p->product_quantity);
        $dateNow = now()->format('M d, Y');
    @endphp

    {{-- Gradient Background --}}
    <style>
        body {
            background: linear-gradient(to right, #3b82f6, #9333ea);
            font-family: 'Poppins', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 1rem;
            padding: 1rem;
        }
        table th, table td {
            padding: 12px 16px;
            text-align: center;
            vertical-align: middle;
        }
        table img {
            border-radius: 8px;
        }
        .header-bar {
            background: rgba(255,255,255,0.20);
            backdrop-filter: blur(10px);
            padding: 12px 24px;
            margin-bottom: 20px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-custom {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
            color: white;
            font-weight: bold;
        }
        .btn-add {
            background-color: #03d3fc;
            color: black;
        }
        .btn-add:hover {
            background-color: #3b82f6;
            color: white;
        }
        .btn-danger { background-color: #e11d48; }
        .btn-danger:hover { background-color: #be123c; }
        .btn-success { background-color: #16a34a; }
        .btn-success:hover { background-color: #15803d; }
    </style>

    {{-- Header --}}
    <x-slot name="header">
        <div class="text-white font-bold text-2xl">
            {{ __('View Products') }}
        </div>
    </x-slot>

    {{-- Totals Header Bar --}}
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-15 mt-6">
        <div class="header-bar">
            <span>Total Products: {{ $totalQuantity }}</span>
            <span>Total Value: ₱{{ number_format($totalValue, 2) }}</span>
            <span>Date: {{ $dateNow }}</span>
            <a href="{{ route('admin.addproduct') }}" class="btn-custom btn-add">+ Add Product</a>
        </div>

        {{-- Table Container --}}
        <div class="glass-card overflow-auto">
            <table class="w-full text-white">
                <thead class="font-semibold bg-opacity-20">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Barcode</th>
                        <th>Supplier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class=" bg-opacity-10">
                    @forelse ($products as $product)
                        <tr class="hover:bg-white hover:bg-opacity-20">
                            <td>
                                <img style="width:80px" src="{{ asset('db_img/'.$product->product_image) }}" alt="{{ $product->product_name }}">
                            </td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->product_description }}</td>
                            <td>{{ $product->product_quantity }}</td>
                            <td>₱{{ number_format($product->product_price,2) }}</td>
                            <td>{{ $product->barcode_id }}</td>
                            <td>{{ $product->supplier_name }}</td>
                            <td>
                                <a href="{{ route('admin.updateproduct', $product->id) }}" class="btn-custom btn-success">Update</a>
                                <a href="{{ route('admin.deleteproduct', $product->id) }}" 
                                   onclick="return confirm('Are you sure you want to delete this product?')"
                                   class="btn-custom btn-danger">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-white">No products available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
z