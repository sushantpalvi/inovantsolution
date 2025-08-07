<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $userId = 1; // Hardcoded user for now
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        // Check if product already exists in cart
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            // Update existing quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();

            return response()->json(['message' => 'Cart updated with new quantity.']);
        } else {
            // Insert new cart item
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);

            return response()->json(['message' => 'Product added to cart.']);
        }
    }

    public function getCartItems()
    {
        $cartItems = Cart::with('product.images')->where('user_id', 1)->get();
        return response()->json([
            'cart_items' => $cartItems
        ]);
    }

}
?>