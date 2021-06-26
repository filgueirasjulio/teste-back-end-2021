<?php

namespace App\Transformers\Products;

use App\Models\Product;
use Flugg\Responder\Transformers\Transformer;

class ProductTransformer extends Transformer
{
  /**
   * Transform the model.
   *
   * @param  \App\Product $product
   * @return array
   */
  public function transform(Product $product)
  {
    return [
      'id' => (int) $product->id,
      'user_id' => (int) $product->user_id,
      'name' => $product->name,
      'price' => $product->price * 100,
      'weight' => $product->weight * 100,
      'image' => $product->image
    ];
  }
}
