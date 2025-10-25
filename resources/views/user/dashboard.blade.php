<x-app-layout>
@php
    // Totals for products
    $totalQuantity = $products->sum('product_quantity');
    $totalValue = $products->sum(function($product) {
        return $product->product_quantity * $product->product_price;
    });

    // Totals for scans
    $totalScanQuantity = $scans->sum('quantity');
    $totalScanValue = $scans->sum(function($scan) {
        return $scan->quantity * $scan->product_price;
    });
@endphp

<style>
    body { background: linear-gradient(to right, #3b82f6, #9333ea); }
    .header-bar {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(12px);
        padding: 8px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 1rem;
        color: #000000;
        font-weight: 500;
        margin-bottom: 12px;
        height: 60px;
    }
    .header-item span { font-weight: bold; color: #000000; }
    .full-table {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        border-radius: 12px;
        padding: 16px;
        height: calc(100vh - 260px);
        overflow-y: auto;
        color: white;
    }
    th, td { padding: 10px; }
    th { background: rgba(255,255,255,0.2); }
    tr:hover { background: rgba(255,255,255,0.1); }
    .search-box input {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        outline: none;
        width: 250px;
        margin-bottom: 10px;
    }
</style>

<x-slot name="header">
    <h2 class="text-2xl font-bold text-white">Admin Dashboard Overview</h2>
</x-slot>

<!-- Unified header bar -->
<div class="header-bar" id="headerBar">
    <div class="header-item">Total Qty: <span id="totalQty">{{ $totalQuantity }}</span></div>
    <div class="header-item">Total Value: <span id="totalValue">₱{{ number_format($totalValue, 2) }}</span></div>
    <div class="header-item">Date: <span>{{ now()->format('M d, Y') }}</span></div>
</div>

<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
    <!-- Dropdown to switch views -->
    <div class="flex justify-end mb-4">
        <select id="viewSelect" class="rounded-md px-3 py-2">
            <option value="products">View Products</option>
            <option value="scanner">Barcode Scanner</option>
        </select>
    </div>

    <!-- Search Box -->
    <div class="search-box flex justify-end mb-4">
        <input type="text" id="searchInput" placeholder="Search...">
    </div>

    <!-- Products Table -->
    <div class="full-table" id="productsView">
        <table class="w-full text-left table-fixed" id="productsTable">
            <thead>
                <tr class=" bg-opacity-20">
                    <th class="w-1/12 p-3 text-center">Image</th>
                    <th class="w-2/12 p-3 text-center">Name</th>
                    <th class="w-2/12 p-3 text-center">Description</th>
                    <th class="w-1/12 p-3 text-center">Qty</th>
                    <th class="w-1/12 p-3 text-center">Price</th>
                    <th class="w-2/12 p-3 text-center">Barcode</th>
                    <th class="w-2/12 p-3 text-center">Supplier</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b border-white border-opacity-20 hover:bg-white hover:bg-opacity-10 text-center">
                    <td class="p-3 flex justify-center">
                        <img class="w-16 h-16 object-cover rounded-md" src="{{ asset('db_img/'.$product->product_image) }}" alt="Product">
                    </td>
                    <td class="p-3">{{ $product->product_name }}</td>
                    <td class="p-3">{{ $product->product_description }}</td>
                    <td class="p-3">{{ $product->product_quantity }}</td>
                    <td class="p-3">₱{{ number_format($product->product_price, 2) }}</td>
                    <td class="p-3">{{ $product->barcode_id }}</td>
                    <td class="p-3">{{ $product->supplier_name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No products available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Barcode Scanner Table -->
    <div class="full-table hidden" id="scannerView">
        <table class="w-full text-left table-fixed" id="scannerTable">
            <thead>
                <tr class=" bg-opacity-20">
                    <th class="w-1/12 p-3 text-center">Image</th>
                    <th class="w-2/12 p-3 text-center">Name</th>
                    <th class="w-2/12 p-3 text-center">Description</th>
                    <th class="w-1/12 p-3 text-center">Qty</th>
                    <th class="w-1/12 p-3 text-center">Price</th>
                    <th class="w-2/12 p-3 text-center">Barcode</th>
                    <th class="w-2/12 p-3 text-center">Supplier</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                <tr class="border-b border-white border-opacity-20 hover:bg-white hover:bg-opacity-10 text-center">
                    <td class="p-3 flex justify-center">
                        <img class="w-16 h-16 object-cover rounded-md" src="{{ asset('db_img/'.$scan->product_image) }}" alt="Product">
                    </td>
                    <td class="p-3">{{ $scan->product_name }}</td>
                    <td class="p-3">{{ $scan->product_description }}</td>
                    <td class="p-3">{{ $scan->quantity }}</td>
                    <td class="p-3">₱{{ number_format($scan->product_price, 2) }}</td>
                    <td class="p-3">{{ $scan->barcode_id }}</td>
                    <td class="p-3">{{ $scan->supplier_name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No scans available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const viewSelect = document.getElementById('viewSelect');
    const productsView = document.getElementById('productsView');
    const scannerView = document.getElementById('scannerView');
    const totalQty = document.getElementById('totalQty');
    const totalValue = document.getElementById('totalValue');

    function updateHeaderTotals(view) {
        if(view === 'products') {
            totalQty.textContent = '{{ $totalQuantity }}';
            totalValue.textContent = '₱{{ number_format($totalValue, 2) }}';
        } else {
            totalQty.textContent = '{{ $totalScanQuantity }}';
            totalValue.textContent = '₱{{ number_format($totalScanValue, 2) }}';
        }
    }

    // Toggle views
    viewSelect.addEventListener('change', function() {
        if (this.value === 'products') {
            productsView.classList.remove('hidden');
            scannerView.classList.add('hidden');
        } else {
            productsView.classList.add('hidden');
            scannerView.classList.remove('hidden');
        }
        updateHeaderTotals(this.value);
    });

    // Live search for both tables
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        const visibleTable = productsView.classList.contains('hidden') ? scannerView : productsView;
        const rows = visibleTable.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j].innerText.toLowerCase().includes(filter)) {
                    match = true;
                    break;
                }
            }
            row.style.display = match ? '' : 'none';
        }
    });
</script>

</x-app-layout>
