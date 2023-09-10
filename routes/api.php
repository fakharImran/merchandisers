<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\MerchandiserTimeSheetController;

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
});