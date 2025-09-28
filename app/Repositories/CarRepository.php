<?php

namespace App\Repositories;

use App\Http\Resources\CarResource;
use App\Interfaces\CarRepositoryInterface;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Condition;
use App\Models\Feature;
use App\Models\Flag;
use App\Models\FuelEconomy;
use App\Models\Horsepower;
use App\Models\Image;
use App\Models\Size;
use App\Models\VehicleStatus;
use App\Models\TransmissionType;
use App\Enums\RefurbishmentStatus;
use App\Enums\Feature as FeatureEnum; 
use App\Enums\Condition as ConditionEnum; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarRepository implements CarRepositoryInterface
{
    // This class will handle database interactions for cars
    private string $locale = "";

    public function all()
    {
        $cars = Car::all();
        return CarResource::collection($cars);
    }

    public function paginate(array $requestData, string $sort_direction, string $sort_by, int $page, int $per_page)
    {
        $this->locale = $requestData['lang'];
        $query = Car::query();

        $search = $requestData['search'] ?? '';
        if (!empty($requestData['search'])) {
            $brand = Brand::where('name->' . $this->locale, 'like', "%{$search}%")->first();
            $model = CarModel::where('name->' . $this->locale, 'like', "%{$search}%")->first();

            if (!empty($brand)) {
                $query->where('brand_id', $brand->id);
            }
            if (empty($brand) && !empty($model)) {
                $query->where('car_model_id', $model->id);
            }
            if (empty($brand) && empty($model)) return ['data' => [], 'count' => 0];
        }

        if (!empty($requestData['years_model'][0]) && !empty($requestData['years_model'][1])) {
            $query->whereBetween('model_year', [
                min($requestData['years_model']),
                max($requestData['years_model'])
            ]);
        }

        if(!empty($requestData['car_types_ids'])){
            $query->whereIn('type_id', $requestData['car_types_ids']);
        }

        if(!empty($requestData['transmission_type_ids'])){
            $query->whereIn('transmission_type_id', $requestData['transmission_type_ids']);
        }

        if(!empty($requestData['kilometers'])){
            $query->where('mileage','<=',$requestData['kilometers']);
        }

        if(!empty($requestData['price_from']) || !empty($requestData['price_to'])) {
            $requestData['price_range'] = [
                $requestData['price_from'] ?? null,
                $requestData['price_to'] ?? null
            ];
        }
        if(!empty($requestData['km_from']) || !empty($requestData['km_to'])) {
            $requestData['mileage_range'] = [
                $requestData['km_from'] ?? null,
                $requestData['km_to'] ?? null
            ];
        }
        if(!empty($requestData['down_payment_from']) || !empty($requestData['down_payment_to'])) {
            $requestData['down_payment_range'] = [
                $requestData['down_payment_from'] ?? null,
                $requestData['down_payment_to'] ?? null
            ];
        }
        $this->filterByRange($query, $requestData);

        // 🔍 Filter by fuel economy range
        if (!empty($requestData['fuel_economy']['min']) && !empty($requestData['fuel_economy']['max'])) {
            $fuelMin = $requestData['fuel_economy']['min'];
            $fuelMax = $requestData['fuel_economy']['max'];

            // Join with fuel_economies table
            $query->whereHas('fuelEconomy', function ($q) use ($fuelMin, $fuelMax) {
                $q->where('min', '<=', $fuelMin)
                    ->where('max', '>=', $fuelMax);
            });
        }

        if(!empty($requestData['transmission'])){
            $transmission = $requestData['transmission'];
            $trans = TransmissionType::where('name->' . $this->locale, 'like', "%{$transmission}%")->first();
            if (!empty($trans)) {
                $query->where('transmission_type_id', $trans->id);
            }
            if (empty($trans)) return ['data' => [], 'count' => 0];
        }

        if(!empty($requestData['brand'])){
            $brand_name = $requestData['brand'];
            $brand = Brand::where('name->' . $this->locale, 'like', "%{$brand_name}%")->first();
            if (!empty($brand)) {
                $query->where('brand_id', $brand->id);
            }
            if (empty($brand)) return ['data' => [], 'count' => 0];
        }

        if(!empty($requestData['model'])){
            $model_name = $requestData['model'];
            $model = CarModel::where('name->' . $this->locale, 'like', "%{$model_name}%")->first();
            if (!empty($model)) {
                $query->where('car_model_id', $model->id);
            }
            if (empty($model)) return ['data' => [], 'count' => 0];
        }
          
        if (!empty($requestData['brand_ids'])) {
            $query->whereIn('brand_id', $requestData['brand_ids']);
        }

        if(!empty($requestData['color'])){
            $color = $requestData['color'];
            $query->where('color->' . $this->locale, 'like', "%{$color}%");
        }

        if(!empty($requestData['location'])){
            $location = $requestData['location'];
            $query->where('location', 'like', "%{$location}%");
        }

        
        if(!empty($requestData['year'])){
            $requestData['model_year'] = $requestData['year'];
        }

        if (!empty($requestData['body_style_ids'])) {
            $query->whereIn('body_style_id', $requestData['body_style_ids']);
        }

        if(!empty($requestData['condition'])){
            $requestData['vehicle_status'] = $requestData['condition'];
        }
        if (!empty($requestData['vehicle_status'])) {
            $vehicleId = $this->getVehicleId($requestData['vehicle_status']);
            if ($vehicleId !== null) {
                $query->where('vehicle_status_id', $vehicleId);
            } else {
                throw new \Exception("Vehicle status not found for '{$requestData['vehicle_status']}'");
            }
        }


        foreach (['search', 'price_range', 'engine_capacity_cc', 'fuel_economy', 'brand_ids', 'body_style_ids',
        'vehicle_status', 'years_model','transmission_type_ids','kilometers','installment','down_payment_range','car_types_ids',
        'brand', 'model', 'color', 'year', 'condition', 'price_from', 'price_to', 'km_from', 'km_to', 'mileage_range',
        'lang', 'down_payment_from', 'down_payment_to', 'down_payment_range', 'transmission', 'location'] as $key) {
            unset($requestData[$key]);
        }

        foreach ($requestData as $key => $value) {
            $query->where($key, $value);
        }

        $count = (clone $query)->count();

        if($page === -1 || $per_page === -1){
            $cars = $query->orderBy($sort_by, $sort_direction)->get();
            return ['data' => CarResource::collection($cars), 'count' => $count];
        }
        $cars = $query->orderBy($sort_by, $sort_direction)
            ->paginate($per_page, ['*'], 'page', $page);

        return ['data' => CarResource::collection($cars), 'count' => $count];
    }

    public function filterByRange(&$query, $requestData)
    {
        $filters = ['price_range'=>'price', 'down_payment_range'=>'down_payment',
        'installment'=>'monthly_installment', 'engine_capacity_cc'=>'engine_capacity_cc', 
        'mileage_range' => 'mileage', 'down_payment_range'=> 'down_payment'];
        foreach($filters as $req => $db){
            if (!empty($requestData[$req])) {
                if (!empty($requestData[$req][0]))
                    $query->where($db, '>=', $requestData[$req][0]);
                if (!empty($requestData[$req][1]))
                    $query->where($db, '<=', $requestData[$req][1]);
            }
        }

    }

    public function get($carId)
    {
        $car = Car::findOrFail($carId);
        return new CarResource($car);
    }

    public function insert(array $carData)
    {

        // length, width, height => insert in table sizes
        // min max fuel economy => insert in table fuel_economies
        // min max horsepower insert in table horsepower
        $size = (!empty($carData['length']) && !empty($carData['width']) && !empty($carData['height'])) ? Size::create([
            'length' => (int) $carData['length'],
            'width' => (int) $carData['width'],
            'height' => (int) $carData['height'],
        ]) : null;
        $fuel = (!empty($carData['min_fuel_economy']) && !empty($carData['max_fuel_economy'])) ? FuelEconomy::create([
            'min' => (int) $carData['min_fuel_economy'],
            'max' => (int) $carData['max_fuel_economy'],
        ]) : null;
        $horsepower = (!empty($carData['min_horse_power']) && !empty($carData['max_horse_power'])) ? Horsepower::create([
            'min' => (int) $carData['min_horse_power'],
            'max' => (int) $carData['max_horse_power'],
        ]) : null;

        // brand, model, model_year,license_expiry_date
        // body_style, type, transmission_type, drive_type, color,
        // engine_type, engine_capacity_cc, mileage,
        // vehicle_status, refurbishment_status, price, discount, monthly_installment, trim
        // make new car
        $carDetails = [
            'brand_id'              => !empty($carData['brand']) ? (int) $carData['brand'] : null,
            'car_model_id'          => !empty($carData['model']) ? (int) $carData['model'] : null,
            'model_year'            => !empty($carData['model_year']) ? (int) $carData['model_year'] : null,
            'license_expire_date'   => $carData['license_expire_date'] ?? null,
            'body_style_id'         => !empty($carData['body_style']) ? (int) $carData['body_style'] : null,
            'type_id'               => !empty($carData['type']) ? (int) $carData['type'] : null,
            'transmission_type_id'  => !empty($carData['transmission_type']) ? (int) $carData['transmission_type'] : null,
            'drive_type_id'         => !empty($carData['drive_type']) ? (int) $carData['drive_type'] : null,
            'color'                 => !empty($carData['color_en']) ? [
                'en' => $carData['color_en'],
                'ar' => $carData['color_ar']
            ] : [],
            'engine_type_id'        => !empty($carData['engine_type']) ? (int) $carData['engine_type'] : null,
            'engine_capacity_cc'    => !empty($carData['engine_capacity']) ? (int) $carData['engine_capacity'] : null,
            'mileage'               => !empty($carData['mileage']) ? (int) $carData['mileage'] : null,
            'size_id'               => $size?->id,
            'fuel_economy_id'       => $fuel?->id,
            'horsepower_id'         => $horsepower?->id,
            'vehicle_status_id'     => !empty($carData['vehicle_status']) ? (int) $carData['vehicle_status'] : null,
            'refurbishment_status'  =>  !empty($carData['refurbishment_status']) ? [
                'en' => $carData['refurbishment_status'],
                'ar' => RefurbishmentStatus::from($carData['refurbishment_status'])->label('ar')
            ] : [],
            'price'                 => isset($carData['price']) ? (float) $carData['price'] : 0,
            'discount'              => isset($carData['discount']) ? (float) $carData['discount'] : 0,
            'monthly_installment'   => isset($carData['monthly_installment']) ? (float) $carData['monthly_installment'] : null,
            'down_payment'          => isset($carData['down_payment']) ? (float) $carData['down_payment'] : null,
            'trim_id'               => !empty($carData['trim']) ? (int) $carData['trim'] : null,
            'owner_id'              => auth()->user() instanceof Admin ? 1 : auth()->user()->id
        ];

        $newCar = Car::create($carDetails);
        // Handle features
        if (!empty($carData['features'])) {
            foreach ($carData['features'] as $feature) {
                if (empty($feature['name'])) continue;
                
                $newCar->features()->create([
                    'name' => $feature['name'],
                    'label' => [
                        'en' => $feature['label']['en'] ?? '',
                        'ar' => $feature['label']['ar'] ?? ''
                    ],
                    'value' => [
                        'en' => $feature['value']['en'] ?? '',
                        'ar' => $feature['value']['ar'] ?? ''
                    ]
                ]);
            }
        }

        // make new flags, conditions for the car
        foreach ($carData['flags'] as $flag) {
            if (empty($flag) || empty(($flag['name_ar'] || $flag['name_en']))) continue;
            $path = null;
            if (!empty($flag['image']))
                $path = $flag['image']->store('flags', 'public');
            $newCar->flags()->create([
                'car_id' => $newCar->id,
                'value' => [
                    'ar' => $flag['name_ar'],
                    'en' => $flag['name_en']
                ],
                'image' => $path
            ]);
        }

        foreach ($carData['conditions'] as $cond) {
            if (empty($cond) || empty($cond['name'])) continue;
            $path = null;
            if (!empty($cond['image']))
                $path = $cond['image']->store('conditions', 'public');
            $newCar->conditions()->create([
                'car_id' => $newCar->id,
                'name' => $cond['name'],
                'part' => $cond['part'] ?? '',
                'description' => $cond['description'] ?? '',
                'image' => $path
            ]);
        }

        // if has images save images and save its location
        if (!empty($carData['images']) && is_array($carData['images'])) {
            foreach ($carData['images'] as $img) {
                if (!$img) continue;
                // Store image in 'public/cars' directory
                $path = $img->store('cars', 'public');

                // Save image path in images table
                $newCar->images()->create([
                    'car_id' => $newCar->id,
                    'location' => $path,
                ]);
            }
        }
        return new CarResource($newCar);
    }

    public function update($carId, $carData)
    {
        // dd($carData) ;
        $car = Car::findOrFail($carId);

        $fuelEco = $this->updateFuelEconomy($carData);
        $horseP  = $this->updateHorsepower($carData);
        $size    = $this->updateSize($carData);

        $this->updateCarMainAttributes($car, $carData, $fuelEco, $horseP, $size);
        $this->syncFlags($car, $carData['flags'] ?? []);
        $this->syncFeatures($car, $carData['features'] ?? []);
        $this->syncConditions($car, $carData['conditions'] ?? []);
        $this->syncImages($car, $carData);

        return new CarResource($car->fresh());
    }

    public function delete($carId)
    {
        $car = Car::findOrFail($carId);
        $car->delete();
    }

    public function getCount()
    {
        return Car::count();
    }

    public function getVehicleId(string $name): ?int
    {
        $jsonPath = '$."' . $this->locale . '"';

        $vehicle = VehicleStatus::whereRaw(
            "JSON_UNQUOTE(JSON_EXTRACT(name, '{$jsonPath}')) = ?",
            [$name]
        )->first();
        return $vehicle?->id;
    }

    protected function updateFuelEconomy(array $data): ?FuelEconomy
    {
        $model = FuelEconomy::find($data['fuel_economy_id'] ?? null);
        if ($model) {
            $model->update([
                'min' => $data['min_fuel_economy'],
                'max' => $data['max_fuel_economy'],
            ]);
        }

        return $model;
    }

    protected function updateHorsepower(array $data): ?Horsepower
    {
        $model = Horsepower::find($data['horsepower_id'] ?? null);
        if ($model) {
            $model->update([
                'min' => $data['min_horse_power'],
                'max' => $data['max_horse_power'],
            ]);
        }
        return $model;
    }

    protected function updateSize(array $data): ?Size
    {
        $model = Size::find($data['size_id'] ?? null);
        if ($model) {
            $model->update([
                'height' => $data['height'],
                'width'  => $data['width'],
                'length' => $data['length'],
            ]);
        }
        return $model;
    }

    protected function updateCarMainAttributes(Car $car, array $data, $fuelEco, $horseP, $size): void
    {
        $car->update([
            'brand_id'              => filled($data['brand']) ? (int) $data['brand'] : null,
            'car_model_id'          => filled($data['model']) ? (int) $data['model'] : null,
            'model_year'            => filled($data['model_year']) ? (int) $data['model_year'] : null,
            'license_expire_date'   => $data['license_expire_date'] ?? null,
            'body_style_id'         => filled($data['body_style']) ? (int) $data['body_style'] : null,
            'type_id'               => filled($data['type']) ? (int) $data['type'] : null,
            'fuel_economy_id'       => $fuelEco?->id,
            'transmission_type_id'  => filled($data['transmission_type']) ? (int) $data['transmission_type'] : null,
            'drive_type_id'         => filled($data['drive_type']) ? (int) $data['drive_type'] : null,
            'engine_type_id'        => filled($data['engine_type']) ? (int) $data['engine_type'] : null,
            'engine_capacity_cc'    => filled($data['engine_capacity']) ? (float) $data['engine_capacity'] : null,
            'color'                 => (!empty($data['color_en']) || !empty($data['color_ar'])) ? [
                'en' => $data['color_en'] ?? '',
                'ar' => $data['color_ar'] ?? ''
            ] : null,
            'size_id'               => $size?->id,
            'mileage'               => filled($data['mileage']) ? (int) $data['mileage'] : null,
            'horsepower_id'         => $horseP?->id,
            'vehicle_status_id'     => filled($data['vehicle_status']) ? (int) $data['vehicle_status'] : null,
            'refurbishment_status'  => $data['refurbishment_status'] ?? null,
            'price'                 => filled($data['price']) ? (float) $data['price'] : null,
            'discount'              => filled($data['discount']) ? (float) $data['discount'] : null,
            'monthly_installment'   => filled($data['monthly_installment']) ? (float) $data['monthly_installment'] : null,
            'down_payment'          => filled($data['down_payment']) ? (float) $data['down_payment'] : null,
            'trim_id'               => filled($data['trim']) ? (int) $data['trim'] : null,
        ]);
    }

    protected function syncFlags(Car $car, array $submittedFlags): void
    {
        try {
            // Start transaction for atomic operations
            \DB::beginTransaction();

            // Get IDs of submitted flags (filter out null/empty values)
            $submittedIds = collect($submittedFlags)
                ->pluck('id')
                ->filter()
                ->values()
                ->toArray();

            // Delete flags that are not in the submitted list
            if (!empty($submittedIds)) {
                $car->flags()
                    ->whereNotIn('id', $submittedIds)
                    ->delete();
            } else {
                // If no flags with IDs are submitted, delete all existing flags
                $car->flags()->delete();
            }

            // Process each submitted flag
            foreach ($submittedFlags as $flagInput) {
                // Skip if both name_ar and name_en are empty
                if (empty($flagInput['name_ar']) && empty($flagInput['name_en'])) {
                    continue;
                }

                // Find existing flag or create new one
                $flagModel = isset($flagInput['id']) 
                    ? $car->flags()->find($flagInput['id']) 
                    : new Flag(['car_id' => $car->id]);

                if (!$flagModel) {
                    continue; // Skip if trying to update non-existent flag
                }

                // Update flag values
                $flagModel->value = [
                    'ar' => $flagInput['name_ar'] ?? '',
                    'en' => $flagInput['name_en'] ?? ''
                ];

                // Handle image upload if present
                if (isset($flagInput['image']) && $flagInput['image'] instanceof \Illuminate\Http\UploadedFile) {
                    // Delete old image if exists
                    if ($flagModel->image) {
                        Storage::disk('public')->delete($flagModel->image);
                    }
                    $flagModel->image = $flagInput['image']->store('flags', 'public');
                }

                $flagModel->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error syncing flags: ' . $e->getMessage());
            throw $e; // Re-throw to be handled by the caller
        }
    }

    protected function syncFeatures(Car $car, array $submittedFeatures): void
    {
        // dd($submittedFeatures);
        $flat = [];
        foreach ($submittedFeatures as $type => $items) {
            foreach ($items as $item) {
                $flat[] = $item;
            }
        }




        $submittedIds = collect($flat)->pluck('id')->filter()->toArray();
        $existing = Feature::where('car_id', $car->id)->get();
        // dd($existing->pluck('id')->toArray(), $submittedIds , $flat);
        foreach ($existing as $feature) {
            if (!in_array($feature->id, $submittedIds)) {
                $feature->delete();
            }
        }

        foreach ($flat as $input) {
            if (empty($input['name'])) continue;

            $feature = !empty($input['id'])
                ? Feature::where('car_id', $car->id)->where('id', $input['id'])->first()
                : new Feature(['car_id' => $car->id]) ;
                
           

            if (!$feature) continue;
            $nameAr = FeatureEnum::from($input['name'])->label('ar');
            $feature->setTranslation('name','en', $input['name'] ?? '');
            $feature->setTranslation('name','ar', $nameAr);
            $feature->setTranslation('label','en', $input['label_en'] ?? '');
            $feature->setTranslation('label','ar', $input['label_ar'] ?? '');
            $feature->setTranslation('value','en', $input['value_en'] ?? '');
            $feature->setTranslation('value','ar', $input['value_ar'] ?? '');
            $feature->save();
        }
    }

    protected function syncConditions(Car $car, array $submittedConditions): void
    {
        // dd($submittedConditions);
        $flat = [];
        foreach ($submittedConditions as $type => $items) {
            foreach ($items as $item) {
                if (empty($item['name'])) continue;
                
                $item['type'] = $type;
                $flat[] = $item;
            }
        }

        // dd($flat);
        $submittedIds = collect($flat)->pluck('id')->filter()->toArray();
        $existing = $car->conditions()->get();
        // dd($submittedIds ,$existing ,$flat);
        foreach ($existing as $condition) {
            if (!in_array($condition->id, $submittedIds)) {
                $condition->delete();
            }
        }

        foreach ($flat as $item) {
            // $conditionData = [
            //     'name' => {}
            //         'en' => $item['name'] ?? '',
            //         'ar' => $item['name_ar'] ?? ''
            //     ],
            //     'part' => json_encode([
            //         'en' => $item['part_en'] ?? '',
            //         'ar' => $item['part_ar'] ?? ''
            //     ]),
            //     'description' => json_encode([
            //         'en' => $item['description_en'] ?? '',
            //         'ar' => $item['description_ar'] ?? ''
            //     ])
            // ];
            // dd($conditionData);
            if (!empty($item['id'])) {
                $model = $car->conditions()->find($item['id']);
                if ($model) {
                    $model->setTranslation('name','ar',$this->getConditionArabicName($item['name']));
                    $model->setTranslation('name','en',$item['name']);
                    $model->setTranslation('part','en',$item['part_en']);
                    $model->setTranslation('part','ar',$item['part_ar']);
                    $model->setTranslation('description','en',$item['description_en']);
                    $model->setTranslation('description','ar',$item['description_ar']);
                    $model->save() ;
                }
            } else {
                $model = $car->conditions()->create([
                    'name' => [
                        'en'=> $item['name'],
                        'ar'=> $this->getConditionArabicName($item['name'])
                    ],
                    'part' => [
                        'en'=> $item['part_en'],
                        'ar'=> $item['part_ar'],
                    ],
                    'description' => 
                    [
                        'en'=>$item['description_en'] ?? '',
                        'ar'=>$item['description_ar'] ?? ''
                    ]
                ]);
            }

            if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $item['image']->store('conditions', 'public');
                $model->update(['image' => $path]);
            }
        }
    }

    protected function syncImages(Car $car, array $data): void
    {
        if (!empty($data['delete_images']) && is_array($data['delete_images'])) {
            foreach ($data['delete_images'] as $deleteId) {
                $image = Image::where('car_id', $car->id)->where('id', $deleteId)->first();
                if ($image) {
                    Storage::disk('public')->delete($image->location);
                    $image->delete();
                }
            }
        }

        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $img) {
                if (!$img instanceof \Illuminate\Http\UploadedFile) continue;
                $path = $img->store('cars', 'public');
                $car->images()->create(['location' => $path]);
            }
        }
    }

    /**
     * Get the Arabic name for a condition
     */
    protected function getConditionArabicName(string $name): string
    {
        try {
            // dd(Condition::from(ucfirst(strtolower($name)))->label('ar'));
            return ConditionEnum::from(ucfirst(strtolower($name)))->label('ar') ?? $name;
        } catch (\ValueError $e) {
            // If the condition name doesn't match any enum case, return the original name
            dd($name,'fall');
            return $name;
        }
    }
}
