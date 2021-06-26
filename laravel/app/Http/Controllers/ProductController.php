<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Actions\Products\getProductAction;
use App\Actions\Products\deleteProductAction;
use App\Actions\Products\storeProductAction;
use App\Actions\Products\updateProductAction;
use App\Transformers\Products\ProductTransformer;

class ProductController extends Controller
{
    /**
     * index
     *
     */
    public function index()
    {
        $products = Product::paginate();

        return responder()->success($products, ProductTransformer::class)->respond();
    }
    
    /**
     * userProducts
     *
     */
    public function userProducts()
    {
        $id = auth()->user()->id;

        $product = Product::where('user_id', $id)->latest()->paginate();

        return responder()->success($product, ProductTransformer::class)->respond();
    }
    
    /**
     * store
     *
     * @param ProductRequest $request
     */
    public function store(ProductRequest $request, storeProductAction $action)
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
    public function update($id, ProductRequest $request, updateProductAction $action)
    {
        try {
            $product = $action->execute($id, $request);
          
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
    public function destroy($id, deleteProductAction $action)
    {
        try {
            $product = $action->execute($id);
          
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
    public function show($id, getProductAction $action)
    {
        try {
            $product = $action->execute($id);

            return responder()->success($product, ProductTransformer::class)->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }
    }
}
