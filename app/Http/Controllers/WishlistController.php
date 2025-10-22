<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlists()->with('product')->get();
        return view('frontend.wishlist', compact('wishlistItems'));
    }

    public function add(Product $product)
    {
        if ($product->stock_count == 0) {
            return back()->with('error', 'Cannot add out of stock product to wishlist.');
        }

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Product already in wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }

    public function remove(Wishlist $wishlist)
    {
        $wishlist->delete();
        return back()->with('success', 'Item removed from wishlist.');
    }
}