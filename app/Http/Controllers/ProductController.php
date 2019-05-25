<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function getIndex()
    {
        $products = Product::all();
        return view('shop.index',compact('products'));
    }
    
    public function getAddToCart(Request $request,$id)
    {
        $product = Product::findOrFail($id);
        $oldCart = Session::has('cart') ? Session :: get('cart') : null ;
        
        $cart = new Cart($oldCart);
    
        $cart->add($product,$product->id);
        
        $request->session()->put('cart',$cart);
       // dd($request->session()->get('cart'));
        return redirect()->route('product.index');
    
    }
    
    public function getCart()
    {
        if ( ! Session::has('cart')){
            return view ('shop.shopping-cart');
        }
        
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view ('shop.shopping-cart',['products'=>$cart->items,'totalPrice'=>$cart->totalPrice]);
    
    
    }
    
    public function getCheckout()
    {
        if ( ! Session::has('cart')){
            return view ('shop.shopping-cart');
        }
        
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart->totalPrice;
        
        return view('shop.checkout',['total'=>$total]);
    }
    
}
