<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Support\Contracts\Action\ActionInterface;

class GetProductAction implements ActionInterface
{
    public function execute($id)
    {
        $product = Product::where('id', $id)->get()->first();
            
          if (is_null($product)) {
              throw new \Exception(trans('messages.products.not_found'), 400);
          }
          
          if ($product->user_id != auth()->user()->id) {    
              throw new \Exception(trans('messages.products.not_permission'), 400);
          }

        return $product;
    }
}
