<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('updateproduct_message'))
                        <div class="bg-gray-800 text-white px-4 py-2 mb-4 rounded">
                            {{ session('updateproduct_message') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.postupdateproduct', $product->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <!-- Old Image -->
                        <div class="form-group mb-4">
                            <label for="old_image">Old Image</label><br>
                            <img style="width:100px;" src="{{ asset('db_img/'.$product->product_image) }}" alt="Product Image">
                        </div>

                        <!-- New Image -->
                        <div class="form-group mb-4">
                            <label for="product_image">Upload New Image</label>
                            <input type="file" class="form-control" id="product_image" name="product_image">
                        </div> 

                        <!-- Product Name -->
                        <div class="form-group mb-4">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" id="product_name" 
                                   name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                        </div>

                        <!-- Product Description -->
                        <div class="form-group mb-4">
                            <label for="product_description">Product Description</label>
                            <textarea class="form-control" name="product_description" rows="4" required>{{ old('product_description', $product->product_description) }}</textarea>
                        </div>

                        <!-- Product Quantity -->
                        <div class="form-group mb-4">
                            <label for="product_quantity">Product Quantity</label>
                            <input type="number" class="form-control" id="product_quantity" 
                                   name="product_quantity" min="1" 
                                   value="{{ old('product_quantity', $product->product_quantity) }}" required>
                        </div>  

                        <!-- Product Price -->
                        <div class="form-group mb-4">
                            <label for="product_price">Product Price</label>
                            <input type="number" class="form-control" id="product_price" 
                                   name="product_price" min="0" 
                                   value="{{ old('product_price', $product->product_price) }}" required>
                        </div> 

                        <!-- Barcode ID -->
                        <div class="form-group mb-4">
                            <label for="barcode_id">Barcode ID</label>
                            <input type="text" class="form-control" id="barcode_id" 
                                   name="barcode_id" placeholder="Enter Barcode ID" 
                                   value="{{ old('barcode_id', $product->barcode_id) }}">
                        </div>

                        <!-- Supplier Selection -->
                        <div class="form-group mb-4">
                            <label for="supplier_name">Supplier</label>
                            <select name="supplier_name" class="form-control" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_name }}" 
                                        {{ old('supplier_name', $product->supplier_name) == $supplier->supplier_name ? 'selected' : '' }}>
                                        {{ $supplier->supplier_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
