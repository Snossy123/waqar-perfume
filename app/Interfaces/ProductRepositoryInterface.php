<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function all();

    public function paginate(array $requestData, string $sort_direction, string $sort_by, int $page, int $per_page);

    public function get($productId);

    public function insert(array $productData);

    public function update($productId, $productData);

    public function delete($productId);

    public function getCount();


}
