<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;

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
        $Products = Product::paginate();

        return responder()->success($Products)->respond();
    }
    
    /**
     * userProducts
     *
     */
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
    public function update($id, ProductRequest $request)
    {
        $product = Product::where('id', $id)->get()->first();
       
        if (is_null($product)) {
            return responder()->error('error', 'O produto não foi encontrado!')->respond();
        }
        
        if ($product->user_id != auth()->user()->id) {
         
            return responder()->error('error', 'Você não tem permissão para editar este produto')->respond();
        };

        $product = $this->service->update($product, $request);
      
        return responder()->success($product)->respond();
    }
    
    /**
     * destroy
     *
     * @param  mixed $id
     */
    public function destroy($id)
    {
        $product = Product::where('id', $id)->get()->first();
       
        if (is_null($product)) {
            return responder()->error('error', 'O produto não foi encontrado!')->respond();
        }
        
        if ($product->user_id != auth()->user()->id) {
         
            return responder()->error('error', 'Você não tem permissão para deletar este produto')->respond();
        };

        $product->delete();

        return responder()->success(['produto deletado com sucesso'])->respond();
    }
    
    /**
     * show
     *
     * @param  mixed $id
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->get()->first();
       
        if (is_null($product)) {
            return responder()->error('error', 'O produto não foi encontrado!')->respond();
        }

        if ($product->user_id != auth()->user()->id) {
            return responder()->error('error', 'Você não tem permissão para visualizar este produto')->respond();
        };

        $product = Product::where('id', $product->id)->latest()->paginate();

        return responder()->success($product)->respond();
    }
}
