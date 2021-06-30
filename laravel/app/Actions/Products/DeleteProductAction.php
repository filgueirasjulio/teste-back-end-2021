<?php

namespace App\Actions\Products;

use App\Actions\Products\GetProductAction;
use App\Support\Contracts\Action\ActionInterface;

class DeleteProductAction implements ActionInterface
{
    public $action;

    public function __construct(GetProductAction $getAction)
    {
        $this->action = $getAction;
    }

    public function execute($id)
    {
        $action = $this->action;

        $product = $action->execute($id);
        
        if(!$product->delete()) {
            throw new \Exception(trans('messages.products.deleted_error'), 400);
        };

        return $product;
    }
}
