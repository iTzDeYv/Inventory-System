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
            font-weight: 500;
            font-size: 16px;
        }
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            
        }
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: none;
            margin-bottom: 16px;
            outline: none;
            
        }
        .form-input:focus {
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
            {{ __('Add Supplier') }}
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 mt-8">
        <div class="glass-card shadow-lg">
            <div class="header-bar">
            Fill in supplier details
            </div>

            <form action="{{ route('admin.postaddsupplier') }}" method="POST">
                @csrf

                <label for="supplier_name" class="form-label">Supplier Name</label>
                <input type="text" id="supplier_name" name="supplier_name" placeholder="Enter Supplier Name" class="form-input" required>

                <label for="supplier_contact" class="form-label">Contact Info</label>
                <input type="text" id="supplier_contact" name="supplier_contact" placeholder="Enter Supplier Contact Info" class="form-input" required>

                <label for="delivery_date" class="form-label">Delivery Date</label>
                <input type="date" id="delivery_date" name="delivery_date" class="form-input" required>

                <button type="submit" class="btn-custom">Add Supplier</button>
            </form>
        </div>
    </div>
</x-app-layout>
