<?php

namespace App\Services;

use App\Interfaces\CarRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class CarService
{
    public function __construct(private CarRepositoryInterface $carRepository)
    {
        // Middleware or other initializations can be done here
    }

    public function all()
    {
        try {
            return $this->carRepository->all();
        } catch (ModelNotFoundException $e) {
            \Log::error('Cars not found: ' . $e->getMessage());
            throw new \Exception('Cars not found.');
        }
    }

    public function paginateCars(array $requestData, string $sort_direction, string $sort_by, int $page, int $per_page)
    {
        try {
            return $this->carRepository->paginate($requestData, $sort_direction, $sort_by, $page, $per_page);
        } catch (ModelNotFoundException $e) {
            \Log::error('Cars not found: ' . $e->getMessage());
            throw new \Exception('Cars not found.');
        }
    }

    // This class will handle car-related services
    public function getCarDetails($carId)
    {
        try {
            return $this->carRepository->get($carId);
        } catch (ModelNotFoundException $e) {
            \Log::error('Car not found: ' . $e->getMessage());
            throw new \Exception('Car not found.');
        }
    }

    public function addNewCar(array $carData)
    {

        try {
            return $this->carRepository->insert($carData);
        } catch (QueryException $e) {
            \Log::error('Error inserting car: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during inserting car data.');
        }
    }

    public function updateCar(int $carId, array $carData)
    {
        try {
            return $this->carRepository->update($carId, $carData);
        } catch (QueryException $e) {
            \Log::error('Error updating car: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during updating car data.');
        } catch (ModelNotFoundException $e) {
            \Log::error('Car not found: ' . $e->getMessage());
            throw new \Exception('Car not found.');
        }
    }

    public function deleteCar($carId)
    {
        try {
            return $this->carRepository->delete($carId);
        } catch (QueryException $e) {
            \Log::error('Error deleting car: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during deleting car data.');
        } catch (ModelNotFoundException $e) {
            \Log::error('Car not found: ' . $e->getMessage());
            throw new \Exception('Car not found.');
        }
    }

    public function formatCar($car)
    {

        $user = auth('api')->user();

        // ✅ هل العربية مفضلة للمستخدم؟
        if ($user) {
            $car->is_fav = $user->favouriteCars()->where('car_id', $car->id)->exists();
        } else {
            $car->is_fav = false;
        }
        // ✅ روابط صور السيارة
        foreach ($car->images as $image) {
            $image->image_url = Storage::url($image->image_path);
            unset($image->image_path);
        }

        // ✅ رابط صورة البراند
        if ($car->brand) {
            $car->brand->image_url = $car->brand->image_path
                ? Storage::url($car->brand->image_path)
                : null;
            unset($car->brand->image_path);
        }

        // ✅ روابط صور الحالات الخارجية
        foreach ($car->exteriorConditions as $condition) {
            $condition->image_url = $condition->image_path
                ? Storage::url($condition->image_path)
                : null;
            unset($condition->image_path);
        }

        // ✅ روابط صور الحالات الداخلية
        foreach ($car->interiorConditions as $condition) {
            $condition->image_url = $condition->image_path
                ? Storage::url($condition->image_path)
                : null;
            unset($condition->image_path);
        }

        // ✅ روابط صور الحالات الميكانيكية
        foreach ($car->mechanicalConditions as $condition) {
            $condition->image_url = $condition->image_path
                ? Storage::url($condition->image_path)
                : null;
            unset($condition->image_path);
        }

        return $car;
    }


    public function getCount()
    {
        try {
            return $this->carRepository->getCount();
        } catch (QueryException $e) {
            \Log::error('Error counting cars: ' . $e->getMessage());
            throw new \Exception('Unexpected Error happened during counting cars.');
        }
    }
}
