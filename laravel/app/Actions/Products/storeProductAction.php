<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Support\Contracts\Action\ActionInterface;

class storeProductAction implements ActionInterface
{
    public function execute($request)
    {        
        $id = auth()->user()->id;
        $request["user_id"] = $id;
  
        if ($request->hasFile('image') && $request->image->isValid()) {
            $request["image"] =  $request->image->store("user/{$id}/products");
        }
       
        $product = Product::create([
                'user_id' => $id,
                'name' => $request['name'],
                'price' => $request['price'],
                'weight' => $request['weight'],
                'image' => $request['image'] ? $request['image'] : null,
        ]);
  
        if (!$product) {
            throw new \Exception(trans('messages.products.updated_error'), 400);
        }

        return $product;
    }
}
