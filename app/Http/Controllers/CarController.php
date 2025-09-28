<?php

namespace App\Http\Controllers;

use App\Enums\Condition;
use App\Enums\Feature;
use App\Enums\RefurbishmentStatus;
use App\Http\Requests\CreateCarRequest;
use App\Http\Requests\PaginatedCarsRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\BodyStyleResource;
use App\Http\Resources\CarResource;
use App\Models\BodyStyle;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\Trim;
use App\Models\Type;
use App\Models\VehicleStatus;
use App\Services\CarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(private CarService $carService)
    {
        // Middleware or other initializations can be done here
    }

    public function store(CreateCarRequest $request)
    {
        $carData = $request->validated();
        
        try {
            $newCar = $this->carService->addNewCar($carData);
            if (request()->expectsJson())
                return response()->json(['message' => 'Car stored successfully', 'data' => $newCar]);
            else
                return redirect()->route('admin.car.show', $newCar->id);
        } catch (\Exception $e) {
            if (request()->expectsJson())
                return response()->json(['message' => 'Error creating car', 'error' => $e->getMessage()], 500);
            else
                return redirect()->back()->with(['message' => 'Error creating car', 'error' => $e->getMessage()])->withInput();
        }
    }


    public function all()
    {
        try {
            $cars = $this->carService->all();
            $count = $this->carService->getCount();
            return response()->json(['message' => 'All cars fetched successfully', 'data' => $cars, 'count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching cars', 'error' => $e->getMessage()], 500);
        }
    }

    public function pagination(PaginatedCarsRequest $request, ?string $sort_direction='asc', ?string $sort_by='created_at', ?int $page=-1, ?int $per_page=-1)
    {
        try {
            $lang = ['lang' => $request->query('lang')
                 ?: config('app.locale')];
            $sort_by = !empty($request->sort_by) ? $request->sort_by : $sort_by;
            $sort_direction = !empty($request->sort_order) ? $request->sort_order : $sort_direction;
            $page = !empty($request->page) ? $request->page : $page;
            $per_page = !empty($request->size) ? $request->size : $per_page;
            $cars = $this->carService->paginateCars($request->validated() + (!empty($request->input('owner_id')) ? ['owner_id' => $request->input('owner_id')] : []) + $lang, $sort_direction, $sort_by, $page, $per_page);
            return response()->json(['message' => 'Cars fetched successfully', 'data' => $cars['data'], 'count' => $cars['count']]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching cars', 'error' => $e->getMessage()], 500);
        }
    }

    public function findById(int $id)
    {
        try {
            $car = $this->carService->getCarDetails($id);
            return response()->json(['message' => 'Car fetched successfully', 'data' => $car]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching car', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit(int $id)
    {
        $car = $this->carService->getCarDetails($id);
        $data = $this->getDropDownData() + ['car' => $this->toRecursiveArray($car)];
        // dd($car);
        // dd($data['car']['features']);
        return view('pages.editCar', $data);
    }


    public function update(int $id, UpdateCarRequest $request)
    {
        // dd($request->all());
        $updatedCarData = $request->validated();
        // dd($id,$updatedCarData);
        try {
            $updatedCar = $this->carService->updateCar($id, $updatedCarData);
            if (request()->expectsJson())
                return response()->json(['message' => 'Car Updated successfully', 'data' => $updatedCar]);
            else
                return redirect()->route('admin.cars')->with('success', 'Car Updated successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson())
                return response()->json(['message' => 'Error Update car', 'error' => $e->getMessage()], 500);
            else
                return redirect()->route('admin.cars')->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->carService->deleteCar($id);
            return redirect()->route('admin.cars')->with('success', 'Car deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.cars')->with('error', $e->getMessage());
        }
    }

    public function add()
    {
        return view('pages.addCar', $this->getDropDownData());
    }

    public function getDropDownData() : array
    {
        return [
            'brands' => Brand::all(),
            'carModels' => CarModel::all(),
            'bodyStyles' => BodyStyleResource::collection(BodyStyle::all()),
            'types' => Type::all(),
            'transmissionTypes' => TransmissionType::all(),
            'driveTypes' => DriveType::all(),
            'engineTypes' => EngineType::all(),
            'vehicleStatuses' => VehicleStatus::all(),
            'refurbishmentStatuses' => RefurbishmentStatus::cases(),
            'trim' => Trim::all(),
            'features' => Feature::cases(),
            'conditions' => Condition::cases()
        ];
    }

    public function show(int $id)
    {
        try {
            $car = $this->carService->getCarDetails($id);
            return view('pages.showCar', ['car' => $this->toRecursiveArray($car)]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'Error fetching car', 'error' => $e->getMessage()]);
        }
    }

    public function index()
    {
        $paginatedCars = Car::with([
            'brand', 'carModel', 'engineType', 'vehicleStatus', 'fuelEconomy',
            'horsepower', 'size', 'trim', 'flags', 'features', 'conditions', 'images'
        ])->latest()->paginate(10);

        $carResources = CarResource::collection($paginatedCars);

        $transformedCars = $carResources->getCollection()->map(function ($car) {
            return $this->toRecursiveArray($car);
        });

        $carResources->setCollection($transformedCars);

        return view('pages.cars', ['cars' => $carResources]);
    }

    public function toRecursiveArray(CarResource $car)
    {
        $carArray = $car->toArray(request());
        $carArray['flags'] = $carArray['flags']->toArray(request());
        $carArray['features'] = $carArray['features'];
        $carArray['conditions'] = $carArray['conditions']->toArray(request());
        $carArray['images'] = $carArray['images']->toArray(request());

        return $carArray;
    }

    public function myCars(PaginatedCarsRequest $request, ?string $sort_direction='asc', ?string $sort_by='created_at', ?int $page=-1, ?int $per_page=-1)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->merge(['owner_id' => $user->id]);
        return $this->pagination($request, $sort_direction, $sort_by, $page, $per_page);
    }
}