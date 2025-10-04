<?php

namespace App\Repositories;

use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\category;
use App\Models\product;
use App\Models\Image;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductRepositoryInterface
{

    public function all()
    {
        $products = product::all();
        return ProductResource::collection($products);
    }

    public function paginate(array $requestData, string $sort_direction, string $sort_by, int $page, int $per_page)
    {
        $query = product::query();

        $search = $requestData['search'] ?? '';
        if (!empty($requestData['search'])) {
            $category_id = category::where('name', 'like', "%{$search}%")->first();

            if (!empty($category_id)) {
                $query->where('category_id', $category_id);
            } else {
                $query->where('name', 'like', "%{$search}%");
            }
        }

        // 🔍 Filter by price range
        $this->filterByRange($query, $requestData);

        
        foreach (['search', 'price_range'] as $key) {
            unset($requestData[$key]);
        }

        foreach ($requestData as $key => $value) {
            $query->where($key, $value);
        }

        $count = (clone $query)->count();

        if($page === -1 || $per_page === -1){
            $products = $query->orderBy($sort_by, $sort_direction)->get();
            return ['data' => ProductResource::collection($products), 'count' => $count];
        }
        $products = $query->orderBy($sort_by, $sort_direction)
            ->paginate($per_page, ['*'], 'page', $page);

        return ['data' => ProductResource::collection($products), 'count' => $count];
    }

    public function filterByRange(&$query, $requestData)
    {
        $filters = ['price_range'=>'price'];
        foreach($filters as $req => $db){
            if (!empty($requestData[$req])) {
                if (!empty($requestData[$req][0]))
                    $query->where($db, '>=', $requestData[$req][0]);
                if (!empty($requestData[$req][1]))
                    $query->where($db, '<=', $requestData[$req][1]);
            }
        }

    }

    public function get($productId)
    {
        $product = product::findOrFail($productId);
        return new ProductResource($product);
    }

    public function insert(array $productData)
    {
        // name, description, price, discounted_price
        // stars, offer_end_date, stock, category_id
        // make new product
        $productDetails = [
            'name'              => !empty($productData['name']) ? (string) $productData['name'] : null,
            'description'       => !empty($productData['description']) ? (string) $productData['description'] : null,
            'price'            => !empty($productData['price']) ? (float) $productData['price'] : null,
            'discounted_price'   => $productData['discounted_price'] ?? 0,
            'stars'         => !empty($productData['stars']) ? (int) $productData['stars'] : null,
            'offer_end_date'               => !empty($productData['offer_end_date']) ? (string) $productData['offer_end_date'] : null,
            'stock'  => !empty($productData['stock']) ? (int) $productData['stock'] : null,
            'category_id'         => !empty($productData['category_id']) ? (int) $productData['category_id'] : null
        ];

        $newProduct = product::create($productDetails);
        
        // if has images save images and save its location
        if (!empty($productData['images']) && is_array($productData['images'])) {
            foreach ($productData['images'] as $img) {
                if (!$img) continue;
                // Store image in 'public/products' directory
                $path = $img->store('products', 'public');

                // Save image path in images table
                $newProduct->images()->create([
                    'product_id' => $newProduct->id,
                    'location' => $path,
                ]);
            }
        }
        return new ProductResource($newProduct);
    }

    public function update($productId, $productData)
    {
        $product = product::findOrFail($productId);

        $this->updateProductMainAttributes($product, $productData);
        $this->syncImages($product, $productData);

        return new ProductResource($product->fresh());
    }

    public function delete($productId)
    {
        $product = product::findOrFail($productId);
        $product->delete();
    }

    public function getCount()
    {
        return product::count();
    }

    protected function updateProductMainAttributes(Product $product, array $data): void
    {
        // name, description, price, discounted_price
        // stars, offer_end_date, stock, category_id
        
        $product->update([
            'name'              => filled($data['name']) ? $data['name'] : null,
            'description'       => filled($data['description']) ? $data['description'] : null,
            'price'            => filled($data['price']) ? (float) $data['price'] : null,
            'discounted_price'   => $data['discounted_price'] ?? 0,
            'stars'         => filled($data['stars']) ? (int) $data['stars'] : null,
            'offer_end_date'               => filled($data['offer_end_date']) ? (string) $data['offer_end_date'] : null,
            'stock'  => filled($data['stock']) ? (int) $data['stock'] : null,
            'category_id'         => filled($data['category_id']) ? (int) $data['category_id'] : null
        ]);
    }

    protected function syncImages(Product $product, array $data): void
    {
        if (!empty($data['delete_images']) && is_array($data['delete_images'])) {
            foreach ($data['delete_images'] as $deleteId) {
                $image = Image::where('product_id', $product->id)->where('id', $deleteId)->first();
                if ($image) {
                    Storage::disk('public')->delete($image->location);
                    $image->delete();
                }
            }
        }

        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $img) {
                if (!$img instanceof \Illuminate\Http\UploadedFile) continue;
                $path = $img->store('products', 'public');
                $product->images()->create(['location' => $path]);
            }
        }
    }
}
