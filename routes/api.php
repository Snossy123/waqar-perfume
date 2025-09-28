<?php

use Illuminate\Support\Facades\Route;
use App\Enums\RefurbishmentStatus;

use App\Http\Controllers\{
    AuthController,
    CarController,
    CalculatorController,
    BookController,
    QuizController,
    QuizAnswerController,
    QuizMatchController,
    StartAdController,
    FinancingRequestController,
    NotificationController as ApiNotificationController,
    SavedSearchController
};

use App\Http\Controllers\Admin\{
    BannerController,
    BrandController,
    BodyStyleController,
    CarModelController,
    DriveTypeController,
    EngineTypeController,
    TransmissionTypeController,
    TrimController,
    TypeController,
    VehicleStatusController,
    PartnerController,
    VideoController
};

use App\Http\Controllers\Draftech\{
    AuthController as DraftechAuthController,
    FavouriteController,
    ContactUsController,
    GovernorateController,
    AreaController,
    UniversityController,
    FacultyController,
    HelpRequestController
};

/**
 * ======================================================
 * AUTHENTICATION ROUTES
 * ======================================================
 */
Route::prefix('auth')->group(function () {
    // Common authentication endpoints
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
    Route::post('/resendOtp', [AuthController::class, 'resendOtp']);
    
    // App-specific login and profile routes
    $isKalksat = config('app.app') === 'kalksat';
    $authController = $isKalksat ? AuthController::class : DraftechAuthController::class;
    
    Route::post('/login', [$authController, 'login']);
    Route::get('/me', [$authController, 'me'])->middleware('auth:api');
    
    // Kalksat-specific auth routes
    if ($isKalksat) {
        Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('/updateProfile', [AuthController::class, 'updateProfile'])->middleware('auth:api');
        Route::get('/refreshToken', [AuthController::class, 'refershToken'])->middleware('auth:api');
        Route::delete('/deleteAccount', [AuthController::class, 'deleteAccount'])->middleware('auth:api');
    }
    
    // Draftech-specific auth routes
    else {
        Route::post('send-otp', [DraftechAuthController::class, 'sendOtp']);
        Route::post('verify-otp', [DraftechAuthController::class, 'verifyOtp']);
        Route::post('update-profile', [DraftechAuthController::class, 'updateProfile'])->middleware('auth:api');
        Route::post('update-password', [DraftechAuthController::class, 'updatePassword'])->middleware('auth:api');
        Route::post('logout', [DraftechAuthController::class, 'logout'])->middleware('auth:api');
        Route::delete('delete-account', [DraftechAuthController::class, 'deleteAccount'])->middleware('auth:api');
        Route::post('/update-phone', [DraftechAuthController::class, 'updatephone']);
    }
    
    // Shared auth-protected routes under auth prefix
    Route::post('/favourites/toggle/{carId}', [FavouriteController::class, 'toggleFavourite'])->middleware('auth:api');
    Route::get('/favourites', [FavouriteController::class, 'myFavourites'])->middleware('auth:api');
    Route::post('/financing-requests', [FinancingRequestController::class, 'store'])->middleware('auth:api');
    Route::get('/requests', [FinancingRequestController::class, 'index'])->middleware('auth:api');
    Route::post('/cancel-requests', [FinancingRequestController::class, 'cancel'])->middleware('auth:api');
    Route::get('saved-searches', [SavedSearchController::class, 'index'])->middleware('auth:api');
    Route::post('saved-searches', [SavedSearchController::class, 'store'])->middleware('auth:api');
    Route::delete('saved-searches/{id}', [SavedSearchController::class, 'destroy'])->middleware('auth:api');
    Route::post('Help-Request', [HelpRequestController::class, 'store'])->middleware('auth:api');
    Route::get('/quiz', [QuizController::class, 'index'])->middleware('auth:api');
    Route::post('/quiz-answers', [QuizAnswerController::class, 'store'])->middleware('auth:api');
    Route::get('/quiz/match', [QuizMatchController::class, 'match'])->middleware('auth:api');
});

/**
 * ======================================================
 * FINANCING REQUESTS ROUTES
 * ======================================================
 */
Route::prefix('financing-requests')->middleware('auth:api')->group(function () {
    Route::post('/', [FinancingRequestController::class, 'store']);
    Route::get('/', [FinancingRequestController::class, 'index']);
    Route::post('/cancel', [FinancingRequestController::class, 'cancel']);
});

/**
 * ======================================================
 * NOTIFICATIONS ROUTES
 * ======================================================
 */
Route::get('notifications/user', [ApiNotificationController::class, 'getForUser'])->middleware('auth:api');

/**
 * ======================================================
 * CARS ROUTES
 * ======================================================
 */
Route::prefix('cars')->group(function () {
    // Public car routes
    Route::get('/', [CarController::class, 'pagination']);
    Route::post('/pagination/{sort_direction?}/{sort_by?}/{page?}/{per_page?}', [CarController::class, 'pagination']);
    Route::get('/{id}', [CarController::class, 'findById']);
    
    // Authenticated car routes
    Route::middleware('auth:api')->group(function () {
        Route::post('/', [CarController::class, 'store']);
        Route::put('/{id}', [CarController::class, 'update']);
        Route::post('/my-cars/{sort_direction?}/{sort_by?}/{page?}/{per_page?}', [CarController::class, 'myCars']);
    });
});

/**
 * ======================================================
 * VEHICLE CONFIGURATION ROUTES
 * ======================================================
 */
Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'indexAPI']);
    Route::get('/{id}', [BrandController::class, 'showAPI']);
});

Route::prefix('body_styles')->group(function () {
    Route::get('/', [BodyStyleController::class, 'indexAPI']);
    Route::get('/{id}', [BodyStyleController::class, 'showAPI']);
});

Route::prefix('models')->group(function () {
    Route::get('/', [CarModelController::class, 'indexAPI']);
    Route::get('/{id}', [CarModelController::class, 'showAPI']);
    Route::post('/', [CarModelController::class, 'getModelsBrandAPI']);
});

Route::prefix('drive_types')->group(function () {
    Route::get('/', [DriveTypeController::class, 'indexAPI']);
    Route::get('/{id}', [DriveTypeController::class, 'showAPI']);
});

Route::prefix('engine_types')->group(function () {
    Route::get('/', [EngineTypeController::class, 'indexAPI']);
    Route::get('/{id}', [EngineTypeController::class, 'showAPI']);
});

Route::prefix('transmission_types')->group(function () {
    Route::get('/', [TransmissionTypeController::class, 'indexAPI']);
    Route::get('/{id}', [TransmissionTypeController::class, 'showAPI']);
});

Route::prefix('trims')->group(function () {
    Route::get('/', [TrimController::class, 'indexAPI']);
    Route::get('/{id}', [TrimController::class, 'showAPI']);
});

Route::prefix('types')->group(function () {
    Route::get('/', [TypeController::class, 'indexAPI']);
    Route::get('/{id}', [TypeController::class, 'showAPI']);
});

Route::prefix('vehicle_statuses')->group(function () {
    Route::get('/', [VehicleStatusController::class, 'indexAPI']);
    Route::get('/{id}', [VehicleStatusController::class, 'showAPI']);
});

Route::prefix('refurbishment_statuses')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'data' => RefurbishmentStatus::cases()
        ]);
    });
    Route::get('/{id}', [VehicleStatusController::class, 'showAPI']);
});

/**
 * ======================================================
 * MARKETING CONTENT ROUTES
 * ======================================================
 */
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/{id}', [BannerController::class, 'show']);
});

Route::prefix('partners')->group(function () {
    Route::get('/', [PartnerController::class, 'index']);
    Route::get('/{id}', [PartnerController::class, 'show']);
});

Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index']);
    Route::get('/{id}', [VideoController::class, 'show']);
});

/**
 * ======================================================
 * CALCULATOR ROUTES
 * ======================================================
 */
Route::prefix('calculator')->group(function () {
    Route::post('/car-installment', [CalculatorController::class, 'calculateInstallment']);
    Route::post('/car-price', [CalculatorController::class, 'calculateCarPrice']);
});

/**
 * ======================================================
 * BOOKING ROUTES
 * ======================================================
 */
Route::prefix('book')->middleware('auth:api')->group(function () {
    Route::post('/', [BookController::class, 'makeAppointment']);
    Route::get('/getBookedCars', [BookController::class, 'getBookedCars']);
});

/**
 * ======================================================
 * QUIZZES ROUTES
 * ======================================================
 */
Route::prefix('quizzes')->middleware('auth:api')->group(function () {
    Route::get('/', [QuizController::class, 'index']);
    Route::post('/answers', [QuizAnswerController::class, 'store']);
    Route::get('/match', [QuizMatchController::class, 'match']);
});

/**
 * ======================================================
 * START AD ROUTES
 * ======================================================
 */
Route::prefix('start-ad')->group(function () {
    Route::get('/', [StartAdController::class, 'show']);
});

/**
 * ======================================================
 * DRAFTECH-SPECIFIC ROUTES
 * ======================================================
 */
Route::post('calculate-car-installment', [CalculatorController::class, 'calculateInstallment']);
Route::post('complete-profile', [DraftechAuthController::class, 'completeRegistration']); 
Route::post('reset-password', [DraftechAuthController::class, 'resetPassword'])->middleware('auth:api');
Route::get('/contact-us', [ContactUsController::class, 'index']);
Route::post('/contact-us', [ContactUsController::class, 'store']);
Route::get('/governorates', [GovernorateController::class, 'index']);
Route::get('/areas', [AreaController::class, 'index']);
Route::get('universities', [UniversityController::class, 'universitiesOnly']);
Route::get('faculties', [FacultyController::class, 'index']);