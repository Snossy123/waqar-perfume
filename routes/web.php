<?php

use App\Http\Controllers\Admin\{
    BodyStyleController,
    BrandController,
    CarModelController,
    DashboardController,
    DriveTypeController,
    EngineTypeController,
    FinancingRequestController,
    TransmissionTypeController,
    TrimController,
    TypeController,
    VehicleStatusController,
    AuthController,
    AdminController,
    RolePermissionController,
    QuizController
};


use App\Http\Controllers\Admin\NotificationController;

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\{CarController, UserController};
use App\Http\Controllers\StartAdController;

use App\Models\Quiz;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('admin.login');
});
Route::get('/login', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->group(function () {
    // Notification routes - protected by admin middleware
    Route::middleware('auth:admin')->group(function () {
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('admin.notifications.index');
            Route::get('/create', [NotificationController::class, 'create'])->name('admin.notifications.create');
            Route::post('/send', [NotificationController::class, 'send'])->name('admin.notifications.send');
        });
    });
    
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});
    // Other admin routes

    // register admin routes here
    // Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('admin.register');
    // Route::post('/register', [AuthController::class, 'register'])->name('admin.submit.register');

    // login route
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.submit.login');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        
        Route::resource('financing-requests', FinancingRequestController::class, [
            'names' => [
                'index' => 'admin.financing-requests.index',
                'show' => 'admin.financing-requests.show',
                'update' => 'admin.financing-requests.update',
                'destroy' => 'admin.financing-requests.destroy'
            ]
        ])->except(['create', 'edit', 'store']);
        
        Route::patch('financing-requests/{financingRequest}/status', [FinancingRequestController::class, 'updateStatus'])
            ->name('admin.financing-requests.update-status');


        Route::prefix('cars')->group(function () {
            Route::get('/', [CarController::class, 'index'])->name('admin.cars');
            Route::get('/{id}', [CarController::class, 'show'])->where(['id'=>'[0-9]+'])->name('admin.car.show');
            Route::get('/add', [CarController::class, 'add'])->name('admin.car.add');
            Route::post('/', [CarController::class, 'store'])->name('admin.car.store');
            Route::get('/edit/{id}', [CarController::class, 'edit'])->name('admin.car.edit');
            Route::put('/{id}', [CarController::class, 'update'])->name('admin.car.update');
            Route::delete('/{id}', [CarController::class, 'destroy'])->name('admin.car.destroy');
        });


        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'showBrands'])->name('admin.brands');
            Route::post('/', [BrandController::class, 'storeBrand'])->name('admin.brands.store');
            Route::put('/{id}', [BrandController::class, 'editBrand'])->name('admin.brands.edit');
            Route::delete('/{id}', [BrandController::class, 'destroyBrand'])->name('admin.brands.destroy');
        });

        Route::prefix('CarModel')->group(function () {
            Route::get('/', [CarModelController::class, 'index'])->name('admin.CarModels');
            Route::post('/', [CarModelController::class, 'store'])->name('admin.CarModel.store');
            Route::put('/{id}', [CarModelController::class, 'edit'])->name('admin.CarModel.edit');
            Route::delete('/{id}', [CarModelController::class, 'destroy'])->name('admin.CarModel.destroy');
        });


        Route::prefix('BodyStyles')->group(function () {
            Route::get('/', [BodyStyleController::class, 'index'])->name('admin.BodyStyles');
            Route::post('/', [BodyStyleController::class, 'store'])->name('admin.BodyStyle.store');
            Route::put('/{id}', [BodyStyleController::class, 'edit'])->name('admin.BodyStyle.edit');
            Route::delete('/{id}', [BodyStyleController::class, 'destroy'])->name('admin.BodyStyle.destroy');
        });


        Route::prefix('Types')->group(function () {
            Route::get('/', [TypeController::class, 'index'])->name('admin.Types');
            Route::post('/', [TypeController::class, 'store'])->name('admin.Type.store');
            Route::put('/{id}', [TypeController::class, 'edit'])->name('admin.Type.edit');
            Route::delete('/{id}', [TypeController::class, 'destroy'])->name('admin.Type.destroy');
        });


        Route::prefix('TransmissionTypes')->group(function () {
            Route::get('/', [TransmissionTypeController::class, 'index'])->name('admin.TransmissionTypes');
            Route::post('/', [TransmissionTypeController::class, 'store'])->name('admin.TransmissionType.store');
            Route::put('/{id}', [TransmissionTypeController::class, 'edit'])->name('admin.TransmissionType.edit');
            Route::delete('/{id}', [TransmissionTypeController::class, 'destroy'])->name('admin.TransmissionType.destroy');
        });


        Route::prefix('DriveTypes')->group(function () {
            Route::get('/', [DriveTypeController::class, 'index'])->name('admin.DriveTypes');
            Route::post('/', [DriveTypeController::class, 'store'])->name('admin.DriveType.store');
            Route::put('/{id}', [DriveTypeController::class, 'edit'])->name('admin.DriveType.edit');
            Route::delete('/{id}', [DriveTypeController::class, 'destroy'])->name('admin.DriveType.destroy');
        });


        Route::prefix('EngineTypes')->group(function () {
            Route::get('/', [EngineTypeController::class, 'index'])->name('admin.EngineTypes');
            Route::post('/', [EngineTypeController::class, 'store'])->name('admin.EngineType.store');
            Route::put('/{id}', [EngineTypeController::class, 'edit'])->name('admin.EngineType.edit');
            Route::delete('/{id}', [EngineTypeController::class, 'destroy'])->name('admin.EngineType.destroy');
        });


        Route::prefix('VehicleStatuses')->group(function () {
            Route::get('/', [VehicleStatusController::class, 'index'])->name('admin.VehicleStatuses');
            Route::post('/', [VehicleStatusController::class, 'store'])->name('admin.VehicleStatus.store');
            Route::put('/{id}', [VehicleStatusController::class, 'edit'])->name('admin.VehicleStatus.edit');
            Route::delete('/{id}', [VehicleStatusController::class, 'destroy'])->name('admin.VehicleStatus.destroy');
        });


        Route::prefix('Trim')->group(function () {
            Route::get('/', [TrimController::class, 'index'])->name('admin.Trim');
            Route::post('/', [TrimController::class, 'store'])->name('admin.Trim.store');
            Route::put('/{id}', [TrimController::class, 'edit'])->name('admin.Trim.edit');
            Route::delete('/{id}', [TrimController::class, 'destroy'])->name('admin.Trim.destroy');
        });

        Route::prefix( 'Banners')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('admin.Banners');
            Route::post('/', [BannerController::class, 'store'])->name('admin.Banner.store');
            Route::put('/{id}', [BannerController::class, 'edit'])->name('admin.Banner.edit');
            Route::delete('/{id}', [BannerController::class, 'destroy'])->name('admin.Banner.destroy');
        });

        Route::prefix('StartAds')->group(function () {
            Route::get('/', [StartAdController::class, 'index'])->name('admin.StartAds');
            Route::post('/', [StartAdController::class, 'store'])->name('admin.StartAd.store');
            Route::put('/{id}', [StartAdController::class, 'edit'])->name('admin.StartAd.edit');
            Route::delete('/{id}', [StartAdController::class, 'destroy'])->name('admin.StartAd.destroy');
        });

        Route::prefix('Users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.Users');
            Route::post('/', [UserController::class, 'store'])->name('admin.User.store');
            Route::put('/{id}', [UserController::class, 'edit'])->name('admin.User.edit');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.User.destroy');
        });

        Route::prefix('Quizzes')->group(function(){
            Route::get('/', [QuizController::class, 'index'])->name('admin.Quizzes');
            Route::get('/create', [QuizController::class, 'create'])->name('admin.quiz.create');
            Route::post('/', [QuizController::class, 'store'])->name('admin.quiz.store');
            Route::get('/{quiz}/edit', [QuizController::class, 'edit'])->name('admin.quiz.edit');
            Route::put('/{quiz}', [QuizController::class, 'update'])->name('admin.quiz.update');
            Route::delete('/{quiz}', [QuizController::class, 'destroy'])->name('admin.quiz.destroy');
        });

        Route::prefix('Admins')->middleware(['role:super-admin'])->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admin.Admins');
            Route::post('/', [AdminController::class, 'store'])->name('admin.Admin.store');
            Route::put('/{id}', [AdminController::class, 'edit'])->name('admin.Admin.edit');
            Route::post('/{id}/assign-role', [AdminController::class, 'assignRole'])->name('admin.Admin.assign.role');
            Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admin.Admin.destroy');
        });

        Route::prefix('Roles')->middleware(['role:super-admin'])->group(function () {
            Route::get('/', [RolePermissionController::class, 'index'])->name('admin.Roles');
            Route::post('/', [RolePermissionController::class, 'store'])->name('admin.Role.store');
            Route::put('/{id}', [RolePermissionController::class, 'update'])->name('admin.Role.edit');
            Route::delete('/{id}', [RolePermissionController::class, 'destroy'])->name('admin.Role.destroy');
        });

        Route::prefix('Permissions')->middleware(['role:super-admin'])->group(function () {
            Route::get('/', [RolePermissionController::class, 'permissionsIndex'])->name('admin.Permissions');
            Route::post('/', [RolePermissionController::class, 'permissionStore'])->name('admin.Permission.store');
            Route::put('/{id}', [RolePermissionController::class, 'permissionUpdate'])->name('admin.Permission.update');
            Route::delete('/{id}', [RolePermissionController::class, 'permissionDestroy'])->name('admin.Permission.destroy');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
});

