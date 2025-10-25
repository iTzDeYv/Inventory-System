<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Barcode Scanner</h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="shadow-sm sm:rounded-lg p-6 flex flex-col lg:flex-row gap-3">

    <!-- Left: Scans Table -->
    <div class="flex-1 overflow-x-auto">
        <table class="w-full border-collapse border-separate" style="border-spacing: 0 15px;">
            <thead class="bg-gray-300 rounded-full">
                <tr class="rounded-xl text-left ">
                    <th class="px-3 py-2">Image</th>
                    <th class="px-3 py-2">Name</th>
                    <th class="px-3 py-2">Description</th>
                    <th class="px-3 py-2">Qty</th>
                    <th class="px-3 py-2">Price</th>
                    <th class="px-3 py-2">Barcode</th>
                    <th class="px-3 py-2">Supplier</th>
                    <th class="px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="scans">
                <!-- Rows will have gap between them due to border-spacing -->
            </tbody>
        </table>
    </div>

    <!-- Right: Controls -->
    <div class="w-80 flex flex-col gap-4">
        <!-- Barcode Input & Buttons -->
        <div class="flex flex-col gap-2 p-4 border rounded bg-gray-50">
            <input type="text" id="barcode" placeholder="Scan barcode" class="border px-3 py-2 w-full">
            <button id="scanBtn" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Scan</button>
            <button id="clearBtn" class="bg-red-600 text-white px-4 py-2 rounded w-full">Clear</button>
            <button id="savePdfBtn" class="bg-green-600 text-white px-4 py-2 rounded w-full">Save PDF</button>
        </div>
        <!-- Totals -->
        <div class="text-left p-4 border rounded bg-gray-50">
            <h3 class="text-lg font-semibold">Total Quantity: <span id="totalQty">0</span></h3>
            <h3 class="text-lg font-semibold">Total Value: $<span id="totalValue">0.00</span></h3>
        </div>
    </div>

</div>




<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

const barcodeInput = document.getElementById('barcode');
const scanBtn = document.getElementById('scanBtn');
const clearBtn = document.getElementById('clearBtn');
const scansTable = document.getElementById('scans'); // tbody for scanned items
const totalQtyCell = document.getElementById('totalQty');
const totalValueCell = document.getElementById('totalValue');

// ------------------ Totals ------------------
function updateTotals() {
    let totalQty = 0, totalValue = 0;
    Array.from(scansTable.rows).forEach(row => {
        const qty = parseInt(row.querySelector('.qty-input').value || 0);
        const price = parseFloat(row.querySelector('.price-cell').innerText.replace('$','') || 0);
        totalQty += qty;
        totalValue += qty * price;
    });
    totalQtyCell.innerText = totalQty;
    totalValueCell.innerText = totalValue.toFixed(2);
}

// ------------------ Quantity Input ------------------
async function qtyChanged(e) {
    const input = e.target;
    const row = input.closest('tr');
    const qty = parseInt(input.value) || 1;
    input.value = qty;

    // send update to backend
    try {
        await axios.post("{{ route('admin.barcode.updateQuantity') }}", {
            barcode_id: row.dataset.barcode,
            quantity: qty
        });
    } catch(err) {
        console.error(err);
        alert('Error updating quantity');
    }

    updateTotals();
}

function qtyEnterPressed(e) {
    if(e.key === 'Enter') {
        e.preventDefault();
        e.target.dispatchEvent(new Event('change'));
        e.target.focus();
    }
}

function attachQtyListeners() {
    scansTable.querySelectorAll('.qty-input').forEach(input => {
        input.removeEventListener('change', qtyChanged);
        input.addEventListener('change', qtyChanged);

        input.removeEventListener('keyup', qtyEnterPressed);
        input.addEventListener('keyup', qtyEnterPressed);
    });
}

// ------------------ Remove Button ------------------
function removeItem(e) {
    const row = e.target.closest('tr');
    if(!confirm('Remove this item?')) return;

    axios.post("{{ route('admin.barcode.remove') }}", { barcode_id: row.dataset.barcode })
        .then(() => {
            row.remove();
            updateTotals();
        })
        .catch(err => {
            console.error(err);
            alert('Error removing item');
        });
}

function attachRemoveListeners() {
    scansTable.querySelectorAll('.removeBtn').forEach(btn => {
        btn.removeEventListener('click', removeItem);
        btn.addEventListener('click', removeItem);
    });
}

// ------------------ Scan Button ------------------
scanBtn.addEventListener('click', async () => {
    const barcode = barcodeInput.value.trim();
    if(!barcode) return;

    // check if item already exists in scans table
    let existingRow = Array.from(scansTable.rows).find(row => String(row.dataset.barcode) === String(barcode));
    if(existingRow) {
        const input = existingRow.querySelector('.qty-input');
        input.value = parseInt(input.value) + 1; // increment
        input.dispatchEvent(new Event('change')); // auto-update backend
        barcodeInput.value = '';
        barcodeInput.focus();
        return;
    }

    try {
        // fetch product from backend
        const res = await axios.post("{{ route('admin.barcode.search') }}", { barcode_id: barcode });
        const product = res.data.product;

        if(!product) {
            alert('Product not found');
            return;
        }

        // add new row
        const row = document.createElement('tr');
        row.dataset.barcode = product.barcode_id;
        row.innerHTML = `
            <td><img src="/db_img/${product.product_image}" class="h-15 w-15 object-cover rounded"></td>
            <td>${product.product_name}</td>
            <td>${product.product_description || '-'}</td>
            <td><input type="number" min="1" value="1" class="qty-input border px-1 py-0.5 w-16 text-center"></td>
            <td class="price-cell">${parseFloat(product.product_price).toFixed(2)}</td>
            <td>${product.barcode_id}</td>
            <td>${product.supplier_name || '-'}</td>
            <td><button class="removeBtn bg-red-500 text-white px-2 py-1 rounded">Remove</button></td>
        `;
        scansTable.prepend(row);

        attachQtyListeners();
        attachRemoveListeners();
        updateTotals();

        // save new product quantity to backend immediately
        await axios.post("{{ route('admin.barcode.updateQuantity') }}", {
            barcode_id: product.barcode_id,
            quantity: 1
        });

        barcodeInput.value = '';
        barcodeInput.focus();
    } catch(err) {
        console.error(err.response || err);
        alert(err.response?.data?.error || 'Error scanning barcode');
    }
});

barcodeInput.addEventListener('keyup', e => { if(e.key==='Enter') scanBtn.click(); });

// ------------------ Clear All ------------------
clearBtn.addEventListener('click', async () => {
    if(!confirm('Clear all scanned items?')) return;

    try {
        await axios.post("{{ route('admin.barcode.clear') }}");
        scansTable.innerHTML = '';
        updateTotals();
        alert('All items cleared successfully!');
    } catch(err) {
        console.error(err);
        alert('Error clearing scan data');
    }
});

// ------------------ Load Saved Scans ------------------
document.addEventListener('DOMContentLoaded', () => {
    const savedScans = @json($scans);

    savedScans.forEach(product => {
        const row = document.createElement('tr');
        row.dataset.barcode = product.barcode_id;
        row.innerHTML = `
            <td><img src="/db_img/${product.product_image}" class="h-12 w-12 object-cover rounded"></td>
            <td>${product.product_name}</td>
            <td>${product.product_description || '-'}</td>
            <td><input type="number" min="1" value="${product.quantity || 1}" class="qty-input border px-1 py-0.5 w-16 text-center"></td>
            <td class="price-cell">${parseFloat(product.product_price).toFixed(2)}</td>
            <td>${product.barcode_id}</td>
            <td>${product.supplier_name || '-'}</td>
            <td><button class="removeBtn bg-red-500 text-white px-2 py-1 rounded">Remove</button></td>
        `;
        scansTable.prepend(row);
    });

    attachQtyListeners();
    attachRemoveListeners();
    updateTotals();
});
document.getElementById('savePdfBtn').addEventListener('click', async () => {
    try {
        const res = await axios.post("{{ route('admin.barcode.savePdf') }}", {}, { responseType: 'blob' });

        const url = window.URL.createObjectURL(new Blob([res.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'scanned-products.pdf');
        document.body.appendChild(link);
        link.click();
        window.URL.revokeObjectURL(url);
        link.remove();
    } catch (err) {
        console.error(err);
        alert('Error generating PDF');
    }
});

</script>

</x-app-layout>
