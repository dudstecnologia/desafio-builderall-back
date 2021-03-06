<?php

namespace App\Repositories;

use App\Models\Product;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class ProductRepository extends BaseRepository
{
    protected $model = Product::class;

    /**
     * Store and return a model newly created
     *
     * @param  Request $request
     * @return Model|null
     */
    public function createAndUpload($request): ?Model
    {
        try {
            $product = $this->model->create($request->except(['image']));

            $product->update([
                'image' => UploadService::saveImageBase64($request->image, UploadService::PRODUCTS)
            ]);

            return $product;
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }

    public function updateProduct($request, $id): ?Model
    {
        try {
            $product = $this->model->find($id);

            $product->update($request->except(['image']));

            if ($request->image) {
                UploadService::deleteFile($product->image);

                $product->update([
                    'image' => UploadService::saveImageBase64($request->image, UploadService::PRODUCTS)
                ]);
            }

            return $product;
        } catch (Throwable $th) {
            return $this->writeLog($th);
        }
    }
}
