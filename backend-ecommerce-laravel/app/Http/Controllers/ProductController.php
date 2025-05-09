<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //

    public function index() 
    {
        $products = Product::query()->orderBy('updated_at', 'desc')->paginate(5);

        return view('product.index', [
            'products' => $products,
        ]);

    }

    public function view(Product $product) 
    {
        return view('product.view', [
            'product' => $product,
        ]);
    }
}
