<?php

namespace App\Interfaces;

interface CarRepositoryInterface
{
    public function all();

    public function paginate(array $requestData, string $sort_direction, string $sort_by, int $page, int $per_page);

    public function get($carId);

    public function insert(array $carData);

    public function update($carId, $carData);

    public function delete($carId);

    public function getCount();


}
