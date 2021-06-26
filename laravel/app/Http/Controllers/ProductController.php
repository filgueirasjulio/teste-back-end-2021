<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use App\Transformers\Products\ProductTransformer;

class ProductController extends Controller
{
    private $service;
    
    /**
     * __construct
     *
     * @param  mixed $service
     * @return void
     */
    public function __construct(ProductService $service)
    {
        $this->middleware('auth:api');
        $this->service = $service;
    }
    
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
     * @param ProductStoreRequest $request
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = $this->service->store($request);
      
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
     * @param ProductStoreRequest $request
     * @param Product $request
     */
    public function update($id, ProductRequest $request)
    {
        try {
            $product = Product::where('id', $id)->get()->first();
       
            if (is_null($product)) {
                throw new \Exception(trans('messages.products.not_found'), 400);
            }
            
            if ($product->user_id != auth()->user()->id) {    
                throw new \Exception(trans('messages.products.not_permission'), 400);
            };
    
            $product = $this->service->update($product, $request);
          
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
    public function destroy($id)
    {
        try {
            $product = Product::where('id', $id)->get()->first();
       
            if (is_null($product)) {
                throw new \Exception(trans('messages.products.not_found'), 400);
            }
            
            if ($product->user_id != auth()->user()->id) {    
                throw new \Exception(trans('messages.products.not_permission'), 400);
            };
    
            $product->delete();

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
    public function show($id)
    {
        try {

            $product = Product::where('id', $id)->get()->first();

            if (is_null($product)) {
                throw new \Exception(trans('messages.products.not_found'), 400);
            }
            
            if ($product->user_id != auth()->user()->id) {    
                throw new \Exception(trans('messages.products.not_permission'), 400);
            };;

            return responder()->success($product, ProductTransformer::class)->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }
    }
}
