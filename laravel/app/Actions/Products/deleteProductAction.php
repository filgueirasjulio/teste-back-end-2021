<?php

namespace App\Actions\Products;

use App\Support\Contracts\Action\ActionInterface;

class deleteProductAction implements ActionInterface
{
    public function execute($id)
    {
        $action = new getProductAction();

        $product = $action->execute($id);
        
        if(!$product->delete()) {
            throw new \Exception(trans('messages.products.deleted_error'), 400);
        };

        return $product;
    }
}
