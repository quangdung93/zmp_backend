<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\GroupProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withFilters(
            request()->input('prices', []),
            request()->input('categories', []),
            request()->input('manufacturers', [])
        )->limit(6)->get();

        return ProductResource::collection($products);
    }

    public function getGroupProducts(){
        $data = Category::with('products')->get();
        
        return response()->json([
            'error' => 0,
            'message' => 'Success!',
            'data' => GroupProductResource::collection($data)
        ]);
    }
}
