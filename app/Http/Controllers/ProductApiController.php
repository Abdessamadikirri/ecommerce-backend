<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductApiController extends Controller
{
    public function index()
    {
        $products=Product::all();
        if( $products){

            return response()->json(ProductResource::collection($products),200);
        }else{
            return response()->json(['message'=>'there is not records in the product model'],200);
        }
    }


    // store a record in the product model 
    public function store(Request $request)
    {
        $request->validate([
        'name'=>'required|string|max:255',
        'description'=>'required|string|max:500',
        'price'=>'required|numeric|min:0.01',
        'stock_quantity'=>'required|integer|min:0',
        'image'=>'nullable|image|mimes:jpg,png,jpeg,svg,webp,gif|max:2028',
        'category'=>'required|string|max:255'
        ]);

        $image='no_image.png';
        if($request->hasFile('image')){
            $imagePath=$request->file('image')->store('images','public');
            Product::create([
                'name'=>$request->input('name'),
                'description'=>$request->input('description'),
                'price'=>$request->input('price'),
                'stock_quantity'=>$request->input('stock_quantity'),
                'image'=>$imagePath,
                'category'=>$request->input('category'),
    
            ]);
        }else{
            Product::create([
                'name'=>$request->input('name'),
                'description'=>$request->input('description'),
                'price'=>$request->input('price'),
                'stock_quantity'=>$request->input('stock_quantity'),
                'image'=>$image,
                'category'=>$request->input('category'),
    
            ]);
        }

        return response()->json(['message'=>'a product has been created seccusefully'],201);

    }


    //update a record in product model 
    public function update($id ,Request $request )
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:500',
            'price'=>'required|numeric|min:0.01',
            'stock_quantity'=>'required|integer|min:0',
            'image'=>'nullable|image|mimes:jpg,png,jpeg,svg,webp,gif|max:2028',
            'category'=>'required|string|max:255'
            ]);
            
        $product=Product::find($id);

        if($request->hasFile('image')){
            $oldimage=public_path('images/'.$product->image);
            if(File::exists($oldimage)){
                
                File::delete($oldimage);
            }

            $fileName= $request->file('image')->store('images','public');

            $product->name=$request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->stock_quantity = $request->input('stock_quantity');
            $product->image = $fileName;
            $product->category = $request->input('category');

            $product->save();
            

        }else{
            $product->name=$request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->stock_quantity = $request->input('stock_quantity');
            $product->category = $request->input('category');

            $product->save();
        };
        

        return response()->json(['message'=>'a record in model porduct has been updated seccusefuly'],200);
    }
    // show product in product controller 
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(new ProductResource($product),200);

    }
    // delete function ins the product controller 
    public function destroy($id)
    {
        $product=Product::findOrFail($id);

        $productImage=storage_path('app/public/'.$product->image);

        
        if(File::exists($productImage)){
            File::delete($productImage);
        }
        
        $product->delete();

        return response()->json(['message'=>'product deleted seccusefully'],200);
    }

}
