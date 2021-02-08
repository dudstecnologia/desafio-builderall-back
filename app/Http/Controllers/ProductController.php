<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductRequest;
use App\Repositories\CartProductRepository;

class ProductController extends Controller
{
    private $productRepository;
    private $cartProductRepository;

    public function __construct(ProductRepository $productRepository, CartProductRepository $cartProductRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartProductRepository = $cartProductRepository;
    }

    /**
     * Display a listing of the product.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$products = $this->productRepository->all()) {
            return response()->json(['error' => 'Could not list products'], 400);
        }

        return response()->json(compact('products'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if (!$product = $this->productRepository->createAndUpload($request)) {
            return response()->json(['error' => 'Couldn\'t save product'], 400);
        }

        return response()->json(compact('product'));
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$product = $this->productRepository->show($id)) {
            return response()->json(['error' => 'Could not show product'], 400);
        }

        return response()->json(compact('product'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        if (!$product = $this->productRepository->updateProduct($request, $id)) {
            return response()->json(['error' => 'Couldn\'t update product'], 400);
        }

        return response()->json(compact('product'));
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->productRepository->destroy($id)) {
            return response()->json(['error' => 'The product could not be removed'], 400);
        }
    }

    public function topSellingProducts()
    {
        if (!$products = $this->cartProductRepository->getTopSellingProducts()) {
            return response()->json([]);
        }

        return response()->json(compact('products'));
    }
}
