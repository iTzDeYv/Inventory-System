<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Products;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use App\Models\Scan;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{

    public function Dashboard()
{
    $products = Products::all();
    $scans = Scan::all(); // or filter by date/user if needed
    return view('admin.dashboard', compact('products', 'scans'));
}

    public function addCategory()
    {
       $categories = \App\Models\Category::all(); // fetch all categories
    return view('admin.addcategory', compact('categories'));
    }
    public function postAddCategory(Request $request) 
    {
         $request->validate([
        'category_name' => 'required|string|max:255',
        'category_id' => 'nullable|integer|exists:categories,id', // optional
    ]);

    if ($request->category_id) {
        // Update existing category
        $category = Category::find($request->category_id);
        $category->category_name = $request->category_name;
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully!');
    } else {
        // Add new category
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();

        return redirect()->back()->with('success', 'Category added successfully!');
    }
    }
    public function viewCategory()
    {
        $categories = Category::all();
        return view('admin.viewcategory',compact('categories'));
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back();
    }

    public function updateCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.updatecategory',compact('category'));
    }
    public function postUpdateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->category_name=$request->category_name;
        $category->save();
        return redirect('/viewcategory');
    }
    public function addSupplier()
    {
        return view('admin.addsupplier');
    }
   public function postAddSupplier(Request $request)
{
    // Validate input (optional but recommended)
    $request->validate([
        'supplier_name' => 'required|string|max:255',
        'supplier_contact' => 'required|string|max:50',
        'delivery_date' => 'nullable|date',
    ]);

    // Create a new supplier
    $supplier = new Supplier();
$supplier->supplier_name = $request->supplier_name;
$supplier->supplier_contact = $request->supplier_contact;
$supplier->delivery_date = $request->delivery_date ?? null;
$supplier->joined_date = $request->joined_date ?? now(); // set to current date if not provided
$supplier->save();

    return redirect()->back()->with('success', 'Supplier added successfully!');
}

    public function viewSupplier()
    {
        $suppliers = Supplier::all(); // note: plural
        return view('admin.viewsupplier', compact('suppliers'));
    }
    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        return redirect()->back();
    }
    public function updateSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.updatesupplier',compact('supplier'));
    }

    public function postUpdateSupplier(Request $request,$id)
    {
       $request->validate([
        'supplier_name' => 'required|string|max:255',
        'supplier_contact' => 'required|string|max:255',
        'delivery_date' => 'required|date',
    ]);

    $supplier = Supplier::findOrFail($id);
    $supplier->update([
        'supplier_name' => $request->supplier_name,
        'supplier_contact' => $request->supplier_contact,
        'delivery_date' => $request->delivery_date,
    ]);

    return redirect()->route('admin.viewsupplier')->with('success', 'Supplier updated successfully!');
    }
public function addProduct()
{
    $categories = Category::all();
    $suppliers = Supplier::all();
    $product = null; // Important line

    return view('admin.addproduct', compact('categories', 'suppliers', 'product'));
}


  public function postAddProduct(Request $request)
{
    // 1. Validate Input
    $request->validate([
        'product_name' => 'required|string|max:255',
        'product_description' => 'required|string',
        'product_quantity' => 'required|integer|min:1',
        'product_price' => 'required|numeric|min:0',
        'barcode_id' => 'nullable|string|max:255', // updated
        'supplier_name' => 'required|string|max:255',
        'product_image' => 'nullable|image|max:2048',
    ]);

    // Save product
    $product = new Products();
    $product->product_name = $request->product_name;
    $product->product_description = $request->product_description;
    $product->product_quantity = $request->product_quantity;
    $product->product_price = $request->product_price;
    $product->barcode_id = $request->barcode_id; // <-- add barcode
    $product->supplier_name = $request->supplier_name;

    if ($request->hasFile('product_image')) {
        $imageName = time() . '.' . $request->product_image->extension();
        $request->product_image->move(public_path('db_img'), $imageName);
        $product->product_image = $imageName;
    }

    $product->save();

    return redirect()->back()->with('addproduct_message', 'Product added successfully!');
}

    public function viewProduct()
    {
        $products = Products::all();
        return view('admin.viewproduct',compact('products'));
    }
    public function deleteProduct($id)
    {
        $product = Products::findOrFail($id);

    // <-- Step 2: Delete the image before deleting the product
    if ($product->product_image && file_exists(public_path($product->product_image))) {
    unlink(public_path($product->product_image));
}


    // Now delete the product record from the database
    $product->delete();

    return redirect()->back()->with('success', 'Product deleted successfully!');
    }
 public function updateProduct($id)
{
    $product = Products::find($id);
    $categories = Category::all();
    $suppliers = Supplier::all();

    // If no product is found, show a 404 error
    if (!$product) {
        abort(404, 'Product not found');
    }

    return view('admin.updateproduct', compact('product', 'categories', 'suppliers'));
}



    public function postUpdateProduct(Request $request, $id)
{
    // 1. Validate input
    $request->validate([
        'product_name' => 'required|string|max:255',
        'product_description' => 'required|string',
        'product_quantity' => 'required|integer|min:1',
        'product_price' => 'required|numeric|min:0',
        'barcode_id' => 'nullable|string|max:255', // added
        'supplier_name' => 'required|string|max:255',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // 2. Find the product
    $product = Products::findOrFail($id);

    // 3. Update product details
    $product->product_name = $request->product_name;
    $product->product_description = $request->product_description;
    $product->product_quantity = $request->product_quantity;
    $product->product_price = $request->product_price;
    $product->barcode_id = $request->barcode_id; // <-- save barcode
    $product->supplier_name = $request->supplier_name;

    // 4. Update image if a new one is uploaded
    if ($request->hasFile('product_image')) {
        $imageName = time() . '.' . $request->product_image->extension();
        $request->product_image->move(public_path('db_img'), $imageName);
        $product->product_image = $imageName;
    }

    // 5. Save the product
    $product->save();

    // 6. Redirect with success message
    return redirect()->route('admin.viewproduct')->with('updateproduct_message', 'Product updated successfully!');

}

// Display the scanner page
public function scan(Request $request)
{
    $barcode = $request->input('barcode');

    $product = Products::where('barcode_id', $barcode)->first();

    if ($product) {
        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Product not found'
    ]);
}

public function scanner() {
    return view('admin.barcode-scanner');
}

public function search(Request $request) {
    $product = Products::where('barcode_id', $request->barcode_id)->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json([
        'product_name' => $product->product_name,
        'product_price' => $product->product_price,
        'supplier_name' => $product->supplier_name,
        'barcode_id' => $product->barcode_id,
    ]);
}



        
    }

