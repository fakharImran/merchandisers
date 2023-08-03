<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CompanyUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',  [CompanyController::class, 'index'])->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// for authentication through email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::group(['middleware' => ['auth']], function() {
    
    Route::resource('company', CompanyController::class);
    Route::get('company/edit/{target?}/{parameter?}', [CompanyController::class, 'edit'])->name('company-edit');
    Route::get('company/delete/{parameter?}', [CompanyController::class, 'delete'])->name('company-delete');
    //users
    Route::resource('user', CompanyUserController::class);
    Route::get('user/edit/{target?}/{parameter?}', [CompanyUserController::class, 'edit'])->name('user-edit');
    Route::get('user/delete/{parameter?}', [CompanyUserController::class, 'delete'])->name('user-delete');

    //stores
    Route::resource('store', StoreController::class);
    Route::get('store/edit/{target?}/{parameter?}', [StoreController::class, 'edit'])->name('store-edit');
    Route::get('store/delete/{parameter?}', [StoreController::class, 'delete'])->name('store-delete');
    Route::get('/export-excel', [ExcelExportController::class, 'export'])->name('export.excel');

    //products
    Route::resource('product', ProductController::class);
    Route::get('product/edit/{target?}/{parameter?}', [ProductController::class, 'edit'])->name('product-edit');
    Route::get('product/delete/{parameter?}', [ProductController::class, 'delete'])->name('product-delete');

});

    Route::get('/file-import',[StoreController::class,
            'importView'])->name('import-view');

    Route::post('/import',[StoreController::class,
            'import'])->name('import');

    Route::get('/export-users',[StoreController::class,
            'exportUsers'])->name('export');



// for forget password feature
// Route::get('/forgot-password', function () {
//     return view('auth.forgot-password');
// })->middleware('guest')->name('password.request');
// Route::post('/forgot-password', function (Request $request) {
//     $request->validate(['email' => 'required|email']);
//     $status = Password::sendResetLink(
//         $request->only('email')
//     );
//     return $status === Password::RESET_LINK_SENT
//                 ? back()->with(['status' => __($status)])
//                 : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('password.email');

// for reset password function 
// Route::get('/reset-password/{token}', function ($token) {
//     return view('auth.reset-password', ['token' => $token]);
// })->middleware('guest')->name('password.reset');
// Route::post('/reset-password', function (Request $request) {
//     $request->validate([
//         'token' => 'required',
//         'email' => 'required|email',
//         'password' => 'required|min:8|confirmed',
//     ]);
//     $status = Password::reset(
//         $request->only('email', 'password', 'password_confirmation', 'token'),
//         function ($user, $password) {
//             $user->forceFill([
//                 'password' => Hash::make($password)
//             ])->save();
//             event(new PasswordReset($user));
//         }
//     );
//     return $status === Password::PASSWORD_RESET
//                 ? redirect()->route('login')->with('status', __($status))
//                 : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('password.update');