<?php

namespace App\Repositories;

use App\Models\CartProduct;
use App\Models\Purchase;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class CartProductRepository extends BaseRepository
{
    protected $model = CartProduct::class;

    public function getTopSellingProducts()
    {
        try {
            $products = $this->model->select(DB::raw('sum(cart_products.quantity) as qt, cart_products.name'))
                ->join('carts', 'carts.id', 'cart_products.cart_id')
                ->join('purchases', 'purchases.id', 'carts.purchase_id')
                ->where('purchases.status', Purchase::CONCLUIDO)
                ->whereBetween('purchases.created_at', [Carbon::now()->subDays(30), Carbon::now()])
                ->groupBy('cart_products.name')
                ->orderBy('qt', 'desc')
                ->limit(10)
                ->get();

            return $products;
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }
}
