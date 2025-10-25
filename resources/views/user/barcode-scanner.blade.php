<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Barcode Scanner</h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex flex-col w-full lg:flex-row">

        <!-- Left: Table -->
        <div class="border rounded shadow-sm w-full">
            <table class="w-full text-left border-collapse ">
                <thead>
                    <tr class="bg-gray-400 ">
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Barcode</th>
                        <th class="px-4 py-2">Supplier</th>
                    </tr>
                </thead>
                <tbody id="scanTable" class="divide-y divide-gray-200"></tbody>
            </table>
        </div>

        <!-- Right: Scan + Totals -->
        <div class="w-full lg:w-80 shadow-md rounded p-4 flex flex-col gap-6">
            
            <!-- Scan Section -->
            <div class="flex gap-2">
                <input type="text" id="barcode" placeholder="Scan barcode" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <button id="scanBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition duration-200">Scan</button>
            </div>

            <!-- Totals -->
            <div class="space-y-2">
                <div class="text-lg font-semibold text-gray-700">Total Quantity: <span id="totalQty">0</span></div>
                <div class="text-lg font-semibold text-gray-700">Total Value: $<span id="totalValue">0.00</span></div>
            </div>

        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

const barcodeInput = document.getElementById('barcode');
const scanBtn = document.getElementById('scanBtn');
const scanTable = document.getElementById('scanTable');
const totalQtyCell = document.getElementById('totalQty');
const totalValueCell = document.getElementById('totalValue');

function updateTotals() {
    let totalQty = 0, totalValue = 0;
    Array.from(scanTable.rows).forEach(row => {
        const qty = parseInt(row.querySelector('.qty-input').value || 0);
        const price = parseFloat(row.querySelector('.price-cell').innerText || 0);
        totalQty += qty;
        totalValue += qty * price;
    });
    totalQtyCell.innerText = totalQty;
    totalValueCell.innerText = totalValue.toFixed(2);
}

function attachQtyListeners() {
    document.querySelectorAll('.qty-input').forEach(input => {
        input.removeEventListener('change', updateTotals);
        input.addEventListener('change', updateTotals);
    });
}

function addRow(product) {
    let existingRow = Array.from(scanTable.rows).find(r => r.dataset.barcode === product.barcode_id);
    if (existingRow) {
        existingRow.querySelector('.qty-input').value = product.quantity;
        existingRow.querySelector('.qty-input').dispatchEvent(new Event('change'));
        return;
    }

    const row = document.createElement('tr');
    row.dataset.barcode = product.barcode_id;
    row.innerHTML = `
        <td><img src="/db_img/${product.product_image}" class="h-20 w-20 object-cover rounded"></td>
        <td class="font-medium">${product.product_name}</td>
        <td>${product.product_description || '-'}</td>
        <td><input type="number" min="1" value="${product.quantity}" class="qty-input border px-2 py-1 w-20 text-center rounded"></td>
        <td class="price-cell">${parseFloat(product.product_price).toFixed(2)}</td>
        <td>${product.barcode_id}</td>
        <td>${product.supplier_name || '-'}</td>
    `;
    scanTable.appendChild(row);
    attachQtyListeners();
    updateTotals();
}

// Scan button click
scanBtn.addEventListener('click', async () => {
    const barcode = barcodeInput.value.trim();
    if (!barcode) return;

    try {
        const res = await axios.post("{{ route('user.barcode.scan') }}", { barcode });
        addRow(res.data.product);
        barcodeInput.value = '';
        barcodeInput.focus();
    } catch (err) {
        alert(err.response?.data?.error || 'Product not found');
    }
});

barcodeInput.addEventListener('keyup', e => { if(e.key==='Enter') scanBtn.click(); });

// Load saved scans
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await axios.get("{{ route('user.barcode.scans') }}");
        res.data.scans.forEach(addRow);
    } catch (err) {
        console.error(err);
    }
});
</script>
</x-app-layout>
