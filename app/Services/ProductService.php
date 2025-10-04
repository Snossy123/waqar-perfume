<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
        // Middleware or other initializations can be done here
    }

    public function all()
    {
        try {
            return $this->productRepository->all();
        } catch (ModelNotFoundException $e) {
            \Log::error('Products not found: ' . $e->getMessage());
            throw new \Exception('Products not found.');
        }
    }

    public function paginateProducts(array $requestData, string $sort_direction, string $sort_by, int $page, int $per_page)
    {
        try {
            return $this->productRepository->paginate($requestData, $sort_direction, $sort_by, $page, $per_page);
        } catch (ModelNotFoundException $e) {
            \Log::error('Products not found: ' . $e->getMessage());
            throw new \Exception('Products not found.');
        }
    }

    // This class will handle product-related services
    public function getProductDetails($productId)
    {
        try {
            return $this->productRepository->get($productId);
        } catch (ModelNotFoundException $e) {
            \Log::error('Product not found: ' . $e->getMessage());
            throw new \Exception('Product not found.');
        }
    }

    public function addNewProduct(array $productData)
    {

        try {
            return $this->productRepository->insert($productData);
        } catch (QueryException $e) {
            \Log::error('Error inserting product: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during inserting product data.');
        }
    }

    public function updateProduct(int $productId, array $productData)
    {
        try {
            return $this->productRepository->update($productId, $productData);
        } catch (QueryException $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during updating product data.');
        } catch (ModelNotFoundException $e) {
            \Log::error('Product not found: ' . $e->getMessage());
            throw new \Exception('Product not found.');
        }
    }

    public function deleteProduct($productId)
    {
        try {
            return $this->productRepository->delete($productId);
        } catch (QueryException $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during deleting product data.');
        } catch (ModelNotFoundException $e) {
            \Log::error('Product not found: ' . $e->getMessage());
            throw new \Exception('Product not found.');
        }
    }

    public function getCount()
    {
        try {
            return $this->productRepository->getCount();
        } catch (QueryException $e) {
            \Log::error('Error counting products: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during counting products.');
        }
    }
}
