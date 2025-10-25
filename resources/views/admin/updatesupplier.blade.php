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
                   <form action="{{ route('admin.postupdatesupplier', $supplier->id) }}" method="post">
                       @csrf 

                       <div class="form-group">
                           <label for="supplier_name">Supplier Name</label>
                           <input type="text" class="form-control" id="supplier_name" 
                                  name="supplier_name" value="{{ $supplier->supplier_name }}" required>
                       </div>

                       <div class="form-group mt-4">
                           <label for="supplier_contact">Contact Info</label>
                           <input type="text" class="form-control" id="supplier_contact" 
                                  name="supplier_contact" value="{{ $supplier->supplier_contact }}" required>
                       </div> 

                       <!-- Add Delivery Date -->
                       <div class="form-group mt-4">
                           <label for="delivery_date">Delivery Date</label>
                           <input type="date" class="form-control" id="delivery_date" 
                                  name="delivery_date" 
                                  value="{{ $supplier->delivery_date ? $supplier->delivery_date->format('Y-m-d') : '' }}" 
                                  required>
                       </div>

                       <button type="submit" class="btn btn-primary mt-4">Update Supplier</button>
                   </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
