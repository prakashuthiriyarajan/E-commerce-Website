<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Product $product)
    {
        if ($product->stock_count <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            if ($cartItem->quantity >= $product->stock_count) {
                return back()->with('error', 'Cannot add more items. Stock limit reached.');
            }
            $cartItem->increment('quantity');
            return back()->with('success', 'Product quantity updated in cart!');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
            return back()->with('success', 'Product added to cart!');
        }
    }

    public function buyNow(Product $product)
    {
        if ($product->stock_count <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        // Add product to cart
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $product->id)
                        ->first();

        if (!$cartItem) {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        // Store product ID in session for checkout
        Session::put('buy_now_product_id', $product->id);
        
        return redirect()->route('checkout.index');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->quantity > $cart->product->stock_count) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $cart->delete();
        return back()->with('success', 'Product removed from cart.');
    }
}