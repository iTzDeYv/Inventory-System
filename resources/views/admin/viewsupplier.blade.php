<x-app-layout>
    @php
        $totalSuppliers = $suppliers->count();
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
            {{ __('View Suppliers') }}
        </div>
    </x-slot>
       
    {{-- Totals Header Bar --}}
        <div class="header-bar">
            <span>Total Suppliers: {{ $totalSuppliers }}</span>
            <span>Date: {{ $dateNow }}</span>
            <a href="{{ route('admin.addsupplier') }}" class="btn-custom btn-add">+ Add Supplier</a>
        </div>
        
        {{-- Table Container --}}
        <div class="glass-card overflow-auto">
            <table class="w-full text-white">
                <thead class="font-semibold bg-opacity-20">
                    <tr>
                        <th>Supplier Name</th>
                        <th>Contact</th>
                        <th>Delivery Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="bg-opacity-10">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-white hover:bg-opacity-20">
                            <td>{{ $supplier->supplier_name }}</td>
                            <td>{{ $supplier->supplier_contact }}</td>
                            <td>{{ $supplier->delivery_date ? $supplier->delivery_date->format('M d, Y') : 'No Date' }}</td>
                            <td>
                                <a href="{{ route('admin.updatesupplier', $supplier->id) }}" class="btn-custom btn-success">Update</a>
                                <a href="{{ route('admin.deletesupplier', $supplier->id) }}" 
                                   onclick="return confirm('Are you sure you want to delete this supplier?')"
                                   class="btn-custom btn-danger">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-white">No suppliers available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
