<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;


class UserBarcodeController extends Controller
{
    public function index()
    {
        return view('user.barcode-scanner');
    }

    public function scan(Request $request)
    {
        $barcode = $request->barcode;

        $product = Products::where('barcode_id', $barcode)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Check if this scan already exists
        $scan = Scan::where('barcode_id', $product->barcode_id)->first();

        if ($scan) {
            $scan->quantity += 1;
            $scan->save();
        } else {
            $scan = Scan::create([
                'barcode_id' => $product->barcode_id,
                'product_name' => $product->product_name,
                'quantity' => 1,
                'product_price' => $product->product_price,
                'product_image' => $product->product_image,
                'product_description' => $product->product_description,
                'supplier_name' => $product->supplier_name,
            ]);
        }

        // Return the scan record so frontend knows the correct quantity
        return response()->json(['product' => $scan]);
    }

    public function getScans()
    {
        $scans = Scan::orderBy('created_at', 'desc')->get();
        return response()->json(['scans' => $scans]);
    }

    public function updateQuantity(Request $request)
{
    $barcode = $request->barcode;
    $quantity = $request->quantity;

    $scan = Scan::where('barcode_id', $barcode)->first();
    if ($scan) {
        $scan->quantity = max(1, intval($quantity));
        $scan->save();
        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Scan not found'], 404);
}
  // Update all scanned quantities
    public function updateAllScans(Request $request)
    {
        $updates = $request->input('updates');

        if (!is_array($updates)) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        foreach ($updates as $item) {
            if (!isset($item['barcode'], $item['quantity'])) continue;

            $scan = Scan::where('barcode_id', $item['barcode'])
                        ->where('user_id', $userId)
                        ->first();

            if ($scan) {
                $scan->quantity = (int)$item['quantity'];
                $scan->save();
            }
        }

        return response()->json(['success' => true]);
    }
}