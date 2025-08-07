<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Model\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function storeForm(Request $request)
    {
        $product = Product::create($request->only(['name', 'price']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect('/product-form')->with('success', 'Product created successfully.');
    }


    public function showForm()
    {
        $products = Product::with('images')->get();
        return view('product-form', compact('products'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Update name and price
        $product->update($request->only(['name', 'price']));

        // Upload new images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads', 'public');
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect('/product-form')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request)
    {
        Product::findOrFail($request->id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function getImages($id)
    {
        $images = ProductImage::where('product_id', $id)->get();
        return response()->json($images);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete record from DB
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    public function list()
    {
        $products = Product::all();
        return view('product-list', compact('products'));
    }
}
?>