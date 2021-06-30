<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Cache;
use App\Actions\Products\GetProductAction;
use App\Actions\Products\StoreProductAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Transformers\Products\ProductTransformer;

class ProductController extends Controller
{
    public $product;

    /**
     * index
     *
     */
    public function index()
    {
        try {
            $products = Product::paginate();

            return responder()->success($products, ProductTransformer::class)->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }  
    }
    
    /**
     * userProducts
     *
     */
    public function userProducts()
    {
        try {
            $id = auth()->user()->id;

            $product = Product::where('user_id', $id)->latest()->paginate();
    
            return responder()->success($product, ProductTransformer::class)->respond();
        
        }   catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }

    }
    
    /**
     * store
     *
     * @param ProductRequest $request
     */
    public function store(ProductRequest $request, StoreProductAction $action)
    {
        try {
            $product = $action->execute($request);

            return responder()
            ->success($product, ProductTransformer::class)
            ->meta(['message' => trans('messages.products.created')])
            ->respond(201);

        }  catch (\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }
    }
    
    /**
     * update
     *
     * @param ProductRequest $request
     */
    public function update($id, ProductRequest $request, UpdateProductAction $action)
    {
        try {
            
            $product = $action->execute($id, $request);

            Cache::set('product_' . $id, $product);
          
            return responder()
                   ->success($product, ProductTransformer::class)
                   ->meta(['message' => trans('messages.products.updated')])
                   ->respond(200);

        } catch (\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }

    }
    
    /**
     * destroy
     *
     * @param  mixed $id
     */
    public function destroy($id, DeleteProductAction $action)
    {
        try {
            $product = $action->execute($id);
            Cache::forget('product_' . $id);
          
            return responder()
            ->success()
            ->meta(['message' => trans('messages.products.deleted')])
            ->respond(200);

        } catch (\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }
    }
    
    /**
     * show
     *
     * @param  mixed $id
     */
    public function show($id, GetProductAction $action)
    {
        try {
            $cachedProduct = Cache::get('product_' . $id);
            
            if (isset($cachedProduct)) {
                $this->product = $cachedProduct;
            } else {
                $this->product = $action->execute($id);
                $cachedProduct = Cache::put('product_' . $id, $this->product, now()->addMinutes(20));
            }
         
            return responder()->success($this->product, ProductTransformer::class)->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }
    }
}
