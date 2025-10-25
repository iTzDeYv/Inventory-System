<?php
namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Scan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class BarcodeController extends Controller
{
    // Show scanner page
 public function scanner()
{
    $scans = Scan::orderBy('created_at', 'desc')->get(); // Fetch saved scans
    return view('admin.barcode-scanner', compact('scans'));
}
public function scan(Request $request)
{
    $request->validate([
        'barcode_id' => 'required|string',
    ]);

    $barcode = $request->barcode_id;

    // Check if scan already exists
    $existingScan = Scan::where('barcode_id', $barcode)->first();

    if ($existingScan) {
        // Increment quantity
        $existingScan->quantity += 1;
        $existingScan->save();

        return response()->json(['product' => $existingScan, 'message' => 'Quantity updated']);
    }

    // If product not scanned yet, fetch product from Products table
    $product = Products::where('barcode_id', $barcode)->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Create new scan entry
    $newScan = Scan::create([
        'barcode_id' => $product->barcode_id,
        'product_name' => $product->product_name,
        'product_description' => $product->product_description,
        'product_price' => $product->product_price,
        'supplier_name' => $product->supplier_name,
        'product_image' => $product->product_image,
        'quantity' => 1
    ]);

    return response()->json(['product' => $newScan, 'message' => 'Product added']);
}


    // Search product and save scan
   
    public function search(Request $request)
{
    $product = Products::where('barcode_id', $request->barcode_id)->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json(['product' => $product]);
}


    // Clear all scans
 public function clear()
{
    try {
        Scan::truncate(); // Deletes all records
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to clear data'], 500);
    }
}
public function remove(Request $request)
{
    try {
        Scan::where('barcode_id', $request->barcode_id)->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete item'], 500);
    }
}
public function savePdf()
{
    $scans = Scan::all();

    // Calculate totals
    $totalQty = $scans->sum('quantity');
    $totalValue = $scans->sum(function ($scan) {
        return $scan->quantity * $scan->product_price;
    });

    $pdf = Pdf::loadView('admin.scanned-pdf', compact('scans', 'totalQty', 'totalValue'));

    return $pdf->download('scanned-products.pdf');
}
public function updateQuantity(Request $request)
{
     $request->validate([
        'barcode_id' => 'required',
        'quantity' => 'required|integer|min:1',
    ]);

    // Only update scanned quantity (not the product master)
    $scan = Scan::where('barcode_id', $request->barcode_id)->first();
    if($scan) {
        $scan->quantity = $request->quantity;
        $scan->save();
    }

    return response()->json(['success' => true]);
}
    
}