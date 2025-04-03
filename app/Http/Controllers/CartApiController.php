<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;
       

        if(!$cart){
            return response()->json(['message'=>'cart not found'],404);
        }
        $cartItem= $cart->cartItems->pluck('product');
        
        if($cartItem->isEmpty()){
            return response()->json(['message'=>'the cart is empty '],200);
        }
        return response()->json(CartItemResource::collection($cartItem), 200);

    } 
    
}
