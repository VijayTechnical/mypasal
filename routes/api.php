<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\ContactsController;
use App\Http\Controllers\User\UserPackController;


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


//Front end routes
Route::get('/categories', [HomeController::class, 'Category']);
Route::get('/locations', [HomeController::class, 'Location']);
Route::post('/featured_posts', [HomeController::class, 'FeaturedPost']);
Route::post('/trending_posts', [HomeController::class, 'TrendingPost']);
Route::post('/recent_posts', [HomeController::class, 'RecentPost']);
Route::get('/sliders', [HomeController::class, 'Slider']);
Route::get('/popups', [HomeController::class, 'Popup']);
Route::get('/setups', [HomeController::class, 'Setup']);
Route::post('/categories/detail/{slug}', [HomeController::class, 'CategoryPost']);
Route::get('/post/detail/{slug}', [HomeController::class, 'PostDetail']);
Route::get('/articles', [HomeController::class, 'Article']);
Route::get('/articles/detail/{slug}', [HomeController::class, 'ArticleDetail']);
Route::get('/faqs', [HomeController::class, 'Faq']);
Route::get('/help', [HomeController::class, 'Help']);
Route::post('/search', [HomeController::class, 'Search']);
Route::get('/vacancies', [HomeController::class, 'Career']);
Route::get('/vacancies/{slug}', [HomeController::class, 'CareerDetail']);


//Application sending route
Route::post('/application/send', [HomeController::class, 'sendApplication']);
//Contact Sending route
Route::post('/contact/send', [HomeController::class, 'sendContact']);

//Auth Routes
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);
Route::post('/password/email', [AuthController::class, 'sendPasswordResetLinkEmail'])->middleware('throttle:5,1');
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

//Social Login Routes
Route::get('/login/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('/login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);





//Routes for authenticated users
Route::middleware(['auth:sanctum'])->group(function () {

    //Profile related routes
    Route::get('/user/dashboard', [UserController::class, 'Dashboard']);
    Route::post('/user/profile/edit/', [UserController::class, 'editProfile']);
    Route::post('/user/password/', [UserController::class, 'changePassword']);

    //User Pack related routes
    Route::get('/user/userpack/', [UserPackController::class, 'index']);
    Route::post('/user/userpack/store', [UserPackController::class, 'store']);
    Route::get('/user/userpack/show/{id}', [UserPackController::class, 'show']);
    Route::post('/user/userpack/edit/{id}', [UserPackController::class, 'update']);
    Route::post('/user/userpack/destroy/{id}', [UserPackController::class, 'destroy']);

    //Post related routes
    Route::get('/user/post/', [PostController::class, 'index']);;
    Route::post('/user/post/store', [PostController::class, 'store']);
    Route::post('/user/post/edit/{id}', [PostController::class, 'update']);
    Route::post('/user/post/destroy/{id}', [PostController::class, 'destroy']);

    //Message Routes
    Route::get('/user/conversation/{id}', [ContactsController::class, 'getMessagesFor']);
    Route::post('/user/conversation/send', [ContactsController::class, 'sendMessage']);

    //Routes to store comments
    Route::get('/post/comment/{id?}', [CommentController::class, 'index']);
    Route::post('/post/comment/store', [CommentController::class, 'store']);
    Route::post('/post/comment/edit/{id}', [CommentController::class, 'update']);
    Route::post('/post/comment/destroy/{id}', [CommentController::class, 'destroy']);

    //Logout
    Route::post('/logout', [AuthController::class, 'Logout']);

    //PAyment Route

    Route::get('payment/init/{ref}', [EsewaController::class, 'initiation'])->name('esewa.init');
    Route::get('payment/success/{ref}', [EsewaController::class, 'completed'])->name('esewa.complete');
    Route::get('payment/failed/{ref}', [EsewaController::class, 'failed'])->name('esewa.failed');
});

