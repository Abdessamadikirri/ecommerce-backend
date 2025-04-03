<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemApiController extends Controller
{
    public function store (Request $request){
        $request->validate([
            'quantity'=>'required|numeric',
            'product_id'=>'required|numeric',
            'price'=>'required|numeric'
        ]);

        $user=Auth::user();
        $cart=$user->cart;
        CartItem::create([
            'cart_id'=>$cart->id,
            'product_id'=>$request->input('product_id'),
            'quantity'=>$request->input('quantity'),
            'price'=>$request->input('price')
        ]);

        return response()->json(['message'=>'cartItem created successfully'],201);
    }
}
