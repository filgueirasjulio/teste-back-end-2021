<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->middleware('auth:api');
        $this->service = $service;
    }

    public function index()
    {
        $Products = Product::paginate();

        return responder()->success($Products)->respond();
    }

    public function userProducts()
    {
        $id = auth()->user()->id;
        $Products = Product::where('user_id', $id)->latest()->paginate();

        return responder()->success($Products)->respond();
    }
    
    /**
     * store
     *
     * @param ProductStoreRequest $request
     */
    public function store(ProductRequest $request)
    {
        $product = $this->service->store($request);
        
        return responder()->success($product)->respond();
    }
    
    /**
     * update
     *
     * @param ProductStoreRequest $request
     * @param Product $request
     */
    public function update(Product $product, ProductRequest $request)
    {
        if ($product->user_id != auth()->user()->id) {
         
            return responder()->error('error', 'Você não tem permissão para editar este produto')->respond();
        };

        $product = $this->service->update($product, $request);
      
        return responder()->success($product)->respond();
    }
}
