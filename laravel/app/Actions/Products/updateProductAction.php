<?php

namespace App\Actions\Products;

use App\Support\Contracts\Action\ActionInterface;

class updateProductAction implements ActionInterface
{
    public function execute($id, $request)
    {
        $action = new getProductAction();

        $product = $action->execute($id);
        
        $idUser = auth()->user()->id;
        $request["user_id"] = $idUser;
  
        if ($request->hasFile('image') && $request->image->isValid()) {
            $request["image"] =  $request->image->store("user/{$id}/products");
        }
       
        $update = $product->update([
                'user_id' => $idUser,
                'name' => $request['name'],
                'price' => $request['price'],
                'weight' => $request['weight'],
                'image' => $request['image'] ? $request['image'] : null,
        ]);

        if (!$update) {
            throw new \Exception(trans('messages.products.updated_error'), 400);
        }

        return $product;
    }
}
