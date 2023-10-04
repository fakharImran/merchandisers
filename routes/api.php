<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\MerchandiserTimeSheetController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\API\MerchandiserApiControllers\ActivityController;
use App\Http\Controllers\API\MerchandiserApiControllers\OutOfStockController;
use App\Http\Controllers\API\MerchandiserApiControllers\PriceAuditController;
use App\Http\Controllers\API\MerchandiserApiControllers\OpportunityController;
use App\Http\Controllers\API\MerchandiserApiControllers\NotificationController;
use App\Http\Controllers\API\MerchandiserApiControllers\MarketingActivityController;
use App\Http\Controllers\API\MerchandiserApiControllers\SellinSelloutDataController;
use App\Http\Controllers\API\MerchandiserApiControllers\StockCountByStoreController;
use App\Http\Controllers\API\MerchandiserApiControllers\ProductExpiryTrackerController;
use App\Http\Controllers\API\MerchandiserApiControllers\PlanogramComplianceTrackerController;

// 'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class, 'register']);
// Route::post('company-validation', [RegisterController::class, 'companyValidator']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('get-companies', [RegisterController::class, 'getCompanies']);
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
// Route::post('password/reset', [ResetPasswordController::class, 'reset']);
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [ResetPasswordController::class, 'reset']);

     
Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('time-sheets', MerchandiserTimeSheetController::class);
    Route::post('time-sheets/check-out/{id}', [MerchandiserTimeSheetController::class, 'update']);


    
    Route::apiResource('price-audit',PriceAuditController::class);
    Route::apiResource('stock-count-by-store',StockCountByStoreController::class);
    Route::apiResource('planogram-compliance-tracker',PlanogramComplianceTrackerController::class);
    Route::post('planogram-compliance-tracker/{id}', [PlanogramComplianceTrackerController::class, 'update']);

    Route::apiResource('sellin-vs-sellout-data',SellinSelloutDataController::class);
    Route::apiResource('marketing-activity',MarketingActivityController::class);
    Route::apiResource('product-expiry-tracker',ProductExpiryTrackerController::class);
    Route::apiResource('out-of-stock',OutOfStockController::class);
    Route::apiResource('opportunity',OpportunityController::class);
    Route::apiResource('notification',NotificationController::class);
    Route::apiResource('activities',ActivityController::class);

});