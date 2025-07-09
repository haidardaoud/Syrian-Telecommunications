<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MediaContentController;
use App\Http\Controllers\MediaPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SelfCareController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Admin Routes
Route::prefix('admin')->group(function () {
    // Pages
    Route::get('pages', [PageController::class, 'index']);
    Route::post('pages', [PageController::class, 'store']);
    Route::get('pages/{id}', [PageController::class, 'show']);
    Route::put('pages/{id}', [PageController::class, 'update']);
    Route::delete('pages/{id}', [PageController::class, 'destroy']);


    // Sections
    Route::get('sections', [SectionController::class, 'index']);         // Get all sections
    Route::post('sections', [SectionController::class, 'store']);       // Create section
    Route::get('sections/{id}', [SectionController::class, 'show']);   // Get section by ID
    Route::put('sections/{id}', [SectionController::class, 'update']); // Update section
    Route::delete('sections/{id}', [SectionController::class, 'destroy']);

    // Contents
    Route::get('contents', [ContentController::class, 'index']);          // Get all contents
    Route::post('contents', [ContentController::class, 'store']);        // Create content
    Route::get('contents/{id}', [ContentController::class, 'show']);    // Get content by ID
    Route::get('contents/sections/{section_title}', [ContentController::class, 'showBySectionName']); // Get paginated content by section name
    Route::put('contents/{id}', [ContentController::class, 'update']);  // Update content
    Route::delete('contents/{id}', [ContentController::class, 'destroy']); // Delete content

    // Media for Pages
    Route::post('pages/{pageId}/media', [MediaPageController::class, 'store']);
    Route::post('media-pages/{id}', [MediaPageController::class, 'update']);
    Route::delete('media-pages/{id}', [MediaPageController::class, 'destroy']);
    Route::get('/media-pages/{pageId}', [MediaPageController::class, 'index']);


    // Media for Contents
    Route::post('media-contents/{contentId}', [MediaContentController::class, 'store']);
    Route::post('media-contents/{id}', [MediaContentController::class, 'update']);
    Route::delete('media-contents/{id}', [MediaContentController::class, 'destroy']);
    Route::get('media-contents/show/{contentId}', [MediaContentController::class, 'show']);

    //create employee
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::put('/employees/{employee_ID}/job', [EmployeeController::class, 'updateJobDetails']);
    Route::delete('/employees/{employee_ID}', [EmployeeController::class, 'destroy']);
    Route::get('/employees/search', [EmployeeController::class, 'getByName']); ///get by name

    Route::post('/impersonate-temporary', [UserController::class, 'impersonateTemporary']);
    Route::delete('/admin/clean-temporary-users', [UserController::class, 'cleanTemporaryUsers']);
    Route::post('/users/{userId}/suspend', [UserController::class, 'suspendUser']);
    Route::post('/users/{userId}/reactivate', [UserController::class, 'reactivateUser']);
    Route::post('/users/{userId}/force-logout', [UserController::class, 'forceLogoutUser']);



   //services
   Route::prefix('services')->group(function () {
    Route::get('/get_all', [ServiceController::class, 'index']);
    Route::post('/add', [ServiceController::class, 'store']);
    Route::get('/show/{id}', [ServiceController::class, 'show']);
    Route::put('/update/{id}', [ServiceController::class, 'update']);
    Route::delete('/delete/{id}', [ServiceController::class, 'destroy']);
});
});

//login
//Route::post('/login', [UserController::class, 'login']);
Route::get('/login', [UserController::class, 'login']);
Route::get('/bundles', [UserController::class, 'index']);

Route::post('/buy', [BillController::class, 'purchase']);// شراء حجوم اضافية
Route::get('/get_bundles', [BundleController::class, 'getUserBundles']);// شراء حجوم اضافية
Route::get('/bills', [UserController::class, 'bills']);
Route::get('/usage-log', [UserController::class, 'show']);
Route::get('/package-info', [UserController::class, 'packageInfo']);
Route::post('/change-password', [UserController::class, 'changePassword']);

//self care
Route::post('/self-care/login', [SelfCareController::class, 'login']);
Route::post('/self-care/services', [SelfCareController::class, 'showServices']);
Route::post('/self-care/bills', [SelfCareController::class, 'showBills']);


// User Routes
Route::prefix('user')->group(function () {
    // Pages
    Route::get('pages', [PageController::class, 'userIndex']);
    Route::get('pages/{id}', [PageController::class, 'userShow']);

    // Sections
    Route::get('sections', [SectionController::class, 'userIndex']);
    Route::get('sections/{id}', [SectionController::class, 'userShow']);

    // Contents
    Route::get('contents', [ContentController::class, 'userIndex']);
    Route::get('contents/{id}', [ContentController::class, 'userShow']);



});

Route::middleware(['auth:sanctum'])->group(function () {
    // Route::post('/change-password', [UserController::class, 'changePassword'])
    //     ->name('password.change')
        //->middleware('throttle:5,1');// منع الهجمات (5 محاولات في الدقيقة)
        Route::post('/logout', [UserController::class, 'logout']);

});
