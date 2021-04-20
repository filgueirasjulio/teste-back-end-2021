<?php

namespace App\Services;

use App\Models\Product;

class ProductService 
{    
    /**
     * store
     *
     * @param  mixed $request
     * @return object
     */
    public function store(object $request)
    {
        $data = $request->all();
        $id = auth()->user()->id;
        $data["user_id"] = $id;
  
        if ($request->hasFile('image') && $request->image->isValid()) {
            $data["image"] =  $request->image->store("user/{$id}/products");
        }

        $product = Product::create($data);

        return $product;
    }

     /**
     * update
     *
     * @param  mixed $request
     * @return object
     */
    public function update(Product $product, object $request)
    {
     
        $data = $request->all();
    
        $id = auth()->user()->id;
        $data["user_id"] = $id;
  
        if ($request->hasFile('image') && $request->image->isValid()) {
            $data["image"] =  $request->image->store("user/{$id}/products");
        }
    
        $product->update($data);

        return $product;
    }
}