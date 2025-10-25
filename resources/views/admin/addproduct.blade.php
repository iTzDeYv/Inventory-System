<x-app-layout>
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
            padding: 2rem;
        }
        .header-bar {
            background: rgba(255,255,255,0.20);
            backdrop-filter: blur(10px);
            padding: 12px 24px;
            margin-bottom: 20px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            font-size: 16px;
        }
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: white;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: none;
            margin-bottom: 16px;
            outline: none;
        }
        .form-textarea {
            resize: vertical;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            box-shadow: 0 0 0 2px rgba(3, 211, 252, 0.5);
        }
        .btn-custom {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            color: black;
            background-color: #03d3fc;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-custom:hover {
            background-color: #3b82f6;
            color: white;
        }
    </style>

    {{-- Header --}}
    <x-slot name="header">
        <div class="text-white font-bold text-2xl">
            {{ __('Add Product') }}
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 mt-8">
        <div class="glass-card shadow-lg">
            <div class="header-bar">
                Fill in product details
            </div>

            @if(session('addproduct_message'))
                <div class="bg-gray-800 text-white px-4 py-2 mb-4 rounded">
                    {{ session('addproduct_message') }}
                </div>
            @endif

            <form action="{{ route('admin.postaddproduct') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Product Image -->
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" id="product_image" name="product_image" class="form-input">

                <!-- Product Name -->
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" id="product_name" name="product_name" placeholder="Enter Product Name" 
                       value="{{ old('product_name') }}" class="form-input" required>

                <!-- Product Description -->
                <label for="product_description" class="form-label">Product Description</label>
                <textarea id="product_description" name="product_description" 
                          placeholder="Enter Product Description" class="form-textarea" required>{{ old('product_description') }}</textarea>

                <!-- Product Quantity -->
                <label for="product_quantity" class="form-label">Product Quantity</label>
                <input type="number" id="product_quantity" name="product_quantity" min="1" 
                       placeholder="Enter Quantity" value="{{ old('product_quantity') }}" class="form-input" required>

                <!-- Product Price -->
                <label for="product_price" class="form-label">Product Price</label>
                <input type="number" id="product_price" name="product_price" min="0" 
                       placeholder="Enter Price" value="{{ old('product_price') }}" class="form-input" required>

                <!-- Barcode ID -->
                <label for="barcode_id" class="form-label">Barcode ID</label>
                <input type="text" id="barcode_id" name="barcode_id" placeholder="Enter Barcode ID" 
                       value="{{ old('barcode_id') }}" class="form-input">

                <!-- Supplier Selection -->
                <label for="supplier_name" class="form-label">Select Supplier</label>
                <select name="supplier_name" id="supplier_name" class="form-select" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_name }}" 
                            {{ old('supplier_name') == $supplier->supplier_name ? 'selected' : '' }}>
                            {{ $supplier->supplier_name }}
                        </option>
                    @endforeach
                </select>

                <!-- Submit Button -->
                <button type="submit" class="btn-custom">Add Product</button>
            </form>
        </div>
    </div>
</x-app-layout>
