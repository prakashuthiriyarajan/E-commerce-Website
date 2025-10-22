<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check if Buy Now - show only that product
        if (Session::has('buy_now_product_id')) {
            $productId = Session::get('buy_now_product_id');
            $cartItem = Cart::with('product')
                           ->where('user_id', Auth::id())
                           ->where('product_id', $productId)
                           ->first();
            
            if (!$cartItem) {
                Session::forget('buy_now_product_id');
                return redirect()->route('home')->with('error', 'Product not found.');
            }
            
            $checkoutItems = collect([$cartItem]);
        } else {
            // Regular checkout - show all cart items
            $checkoutItems = Cart::with('product')->where('user_id', Auth::id())->get();
            
            if ($checkoutItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
        }
        
        $subtotal = $checkoutItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        $shipping = $subtotal > 500 ? 0 : 50;
        $tax = $subtotal * 0.18;
        $total = $subtotal + $shipping + $tax;
        
        $addresses = Address::where('user_id', Auth::id())->get();
        
        return view('checkout.index', compact('checkoutItems', 'subtotal', 'shipping', 'tax', 'total', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = Address::where('id', $request->address_id)
                         ->where('user_id', Auth::id())
                         ->first();

        if (!$address) {
            return back()->with('error', 'Invalid address selected.');
        }

        // Get items for order
        if (Session::has('buy_now_product_id')) {
            $productId = Session::get('buy_now_product_id');
            $orderItems = Cart::with('product')
                             ->where('user_id', Auth::id())
                             ->where('product_id', $productId)
                             ->get();
        } else {
            $orderItems = Cart::with('product')->where('user_id', Auth::id())->get();
        }

        if ($orderItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items to order.');
        }

        // Validate stock
        foreach ($orderItems as $item) {
            if ($item->product->stock_count < $item->quantity) {
                return back()->with('error', $item->product->name . ' is out of stock.');
            }
        }

        $totalAmount = $orderItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        $shipping = $totalAmount > 500 ? 0 : 50;
        $tax = $totalAmount * 0.18;
        $totalAmount = $totalAmount + $shipping + $tax;

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $address->id,
            'total_amount' => $totalAmount,
            'status' => 'On Process',
        ]);

        // Create order items and reduce stock
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $item->product->decrement('stock_count', $item->quantity);
            
            // Remove from cart
            $item->delete();
        }

        // Clear session
        Session::forget('buy_now_product_id');

        return redirect()->route('user.orders')
            ->with('success', 'Order placed successfully! Order ID: #' . $order->id);
    }
}
