<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ProductContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductFormRequest;

class ProductController extends Controller
{
    //
    protected $productRepository;
    public function __construct(ProductContract $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index()
    {
        $products = $this->productRepository->listProducts();
        return response()->json(["getRecords" => $products]);  
    }
    public function store(StoreProductFormRequest $request)
        {
            $params = $request->except('_token');
            $products=[];$status=0;$msg="Something went wrong!!";
            $product = $this->productRepository->createOrUpdateProduct($params);
            if ($product) {
                $status=1;$msg="Success";
                $products = $this->productRepository->listProducts(); 
            }
            return response()
            ->json(["status" => $status,"getRecords" => $products,"msg" => $msg]);
        }
    public function delete(Request $request){
        $products=[];$status=0;$msg="Something went wrong!!";
        $product=$this->productRepository->deleteProduct($request->product_id);
        if($product){
            $status=1;$msg="Success";
            $products = $this->productRepository->listProducts();
        }
        return response()
        ->json(["status" => $status,"getRecords" => $products,"msg" => $msg]);
    }    
}
