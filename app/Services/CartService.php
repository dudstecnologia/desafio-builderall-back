<?php

namespace App\Services;

use App\Models\Purchase;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use Illuminate\Support\Facades\Log;

class CartService
{
	private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

	public function store(Request $request, Purchase $purchase)
	{
		try {
			$total = 0;
			$cart = $purchase->cart()->create(compact('total'));

			foreach ($request->cart as $item) {
				$product = $this->productRepository->show($item['id']);

				if ($item['qt_cart'] > $product->quantity) {
					return "The quantity ordered for product $product->name is greater than that available from stock.";
				}

				$total += $product->price * $item['qt_cart'];
				$quantity = $product->quantity -= $item['qt_cart'];

				$cart->cartProducts()->create([
					'name' => $product->name,
					'image' => $product->image,
					'price' => $product->price,
					'quantity' => $item['qt_cart']
				]);

				$product->update(compact('quantity'));
			}

			return $total;
		} catch (Throwable $th) {
			Log::error($th->getMessage());
			return null;
		}
	}
}