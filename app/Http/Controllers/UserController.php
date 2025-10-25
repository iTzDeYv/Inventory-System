<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;
use App\Models\Products;
// For generating PDFs
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class UserController extends Controller
{
    // Make sure only authenticated users can access

    // Dashboard
   public function dashboard()
{
    $products = Products::all();
     $scans = Scan::all(); // or filter as needed
    return view('user.dashboard', compact('products','scans'));
}

    // Show barcode scanner
    public function barcodeScanner()
    {
        $user = Auth::user(); // Properly get the logged-in user
        $scans = Scan::where('user_id', $user->id)->get();
        return view('user.barcode-scanner', compact('scans'));
    }

    // Barcode search API
    public function barcodeSearch(Request $request)
    {
        $request->validate(['barcode_id' => 'required|string']);
        $user = Auth::user();

        // Look for the product by barcode
        $product = Products::where('barcode_id', $request->barcode_id)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Check if this product was already scanned by the user
        $scan = Scan::firstOrNew([
            'user_id' => $user->id,
            'barcode_id' => $product->barcode_id,
        ]);

        $scan->product_name = $product->product_name;
        $scan->product_description = $product->product_description;
        $scan->product_price = $product->product_price;
        $scan->product_quantity = $scan->exists ? $scan->product_quantity + 1 : 1; // increment
        $scan->db_img = $product->db_img;
        $scan->supplier_name = $product->supplier_name;
        $scan->save();

        return response()->json($scan);
    }

    // Generate PDF of scanned products
    public function barcodeScannerPDF()
    {
        $user = Auth::user();
        $scans = Scan::where('user_id', $user->id)->get();

        if ($scans->isEmpty()) {
            return redirect()->back()->with('error', 'No scanned products to generate PDF.');
        }

        $pdf = PDF::loadView('user.barcode-pdf', compact('scans'));
        return $pdf->download('scanned_products.pdf');
    }

    // Optional: clear scanned products
    public function clearScans()
    {
        $user = Auth::user();
        Scan::where('user_id', $user->id)->delete();

        return redirect()->back()->with('success', 'Scanned list cleared.');
    }
}
