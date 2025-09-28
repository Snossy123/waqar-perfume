<?php

namespace App\Models;

use App\Enums\RefurbishmentStatus;
use App\Models\CarExteriorCondition;
use App\Models\CarInteriorCondition;
use App\Models\CarMechanicalCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Car extends Model
{
    use HasFactory,HasTranslations;
    protected $fillable = [
        'brand_id',
        'car_model_id',
        'model_year',
        'license_expire_date',
        'body_style_id',
        'type_id',
        'transmission_type_id',
        'drive_type_id',
        'color',
        'engine_type_id',
        'engine_capacity_cc',
        'mileage',
        'size_id',
        'fuel_economy_id',
        'horsepower_id',
        'vehicle_status_id',
        'refurbishment_status',
        'price',
        'discount',
        'monthly_installment',
        'trim_id',
        'down_payment',
        'owner_id',
        'vehicle_category',
        'description',
        'location',
        'payment_option'
    ];

    public $translatable = ['color','refurbishment_status'];

    // protected $casts = [
    //     'refurbishment_status' => RefurbishmentStatus::class,
    // ];

    public function flags()
    {
        return $this->hasMany(Flag::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function conditions()
    {
        return $this->hasMany(Condition::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function horsepower()
    {
        return $this->belongsTo(Horsepower::class);
    }

    public function fuelEconomy()
    {
        return $this->belongsTo(FuelEconomy::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function bodyStyle()
    {
        return $this->belongsTo(BodyStyle::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }

    public function driveType()
    {
        return $this->belongsTo(DriveType::class);
    }

    public function exteriorConditions()
    {
        return $this->hasMany(CarExteriorCondition::class);
    }

    public function interiorConditions()
    {
        return $this->hasMany(CarInteriorCondition::class);
    }

    public function mechanicalConditions()
    {
        return $this->hasMany(CarMechanicalCondition::class);
    }

    public function engineType()
    {
        return $this->belongsTo(EngineType::class);
    }

    public function trim()
    {
        return $this->belongsTo(Trim::class);
    }

    public function vehicleStatus()
    {
        return $this->belongsTo(VehicleStatus::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
