<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    // Show the barcode scanner page
    public function index()
    {
        if(Auth::user()->type == 'admin'){
            $scans = Scan::orderBy('created_at','desc')->get();
        } else {
            $scans = Scan::where('user_id', Auth::id())->orderBy('created_at','desc')->get();
        }
        return view('barcode-scanner', compact('scans'));
    }

    // Search / Add scan
    public function search(Request $request)
    {
        $barcode = $request->barcode_id;
        $product = Products::where('barcode_id', $barcode)->first();

        if(!$product) return response()->json(null,404);

        // Save scan
        $scan = Scan::updateOrCreate(
            ['barcode_id'=>$product->barcode_id, 'user_id'=>Auth::id()],
            [
                'product_name'=>$product->product_name,
                'product_price'=>$product->product_price,
                'product_image'=>$product->product_image,
                'supplier_name'=>$product->supplier_name,
                'quantity'=>DB::raw('quantity + 1'),
                'scanned_at'=>now()
            ]
        );

        return response()->json([
            'barcode_id'=>$product->barcode_id,
            'product_name'=>$product->product_name,
            'product_price'=>$product->product_price,
            'product_image'=>$product->product_image,
            'supplier_name'=>$product->supplier_name,
            'quantity'=>$scan->quantity,
            'scanned_at'=>$scan->scanned_at
        ]);
    }

    // Update scan details (admin or user)
    public function update(Request $request, $id)
    {
        $scan = Scan::findOrFail($id);
        $scan->update($request->only(['product_name','product_price','supplier_name','quantity']));
        return response()->json(['status'=>'ok','scan'=>$scan]);
    }

    // Clear scans (admin can clear all, user can clear theirs)
    public function clear()
    {
        if(Auth::user()->type=='admin'){
            Scan::truncate();
        } else {
            Scan::where('user_id', Auth::id())->delete();
        }
        return response()->json(['status'=>'ok']);
    }
}
