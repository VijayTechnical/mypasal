<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\SetupController;
use App\Http\Controllers\Admin\AdpackController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\VacancyController;
use App\Http\Controllers\User\ContactsController;
use App\Http\Controllers\User\UserPackController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PostMetaController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\CustomFieldController;
use App\Http\Controllers\Admin\AdvertisementController;

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
Route::get('/featured_posts', [HomeController::class, 'FeaturedPost']);
Route::get('/trending_posts', [HomeController::class, 'TrendingPost']);
Route::get('/recent_posts', [HomeController::class, 'RecentPost']);
Route::get('/sliders', [HomeController::class, 'Slider']);
Route::get('/popup', [HomeController::class, 'Popup']);
Route::get('/setup', [HomeController::class, 'Setup']);
Route::get('/categories/{slug}', [HomeController::class, 'CategoryPost']);
Route::get('/post/detail/{slug}', [HomeController::class, 'PostDetail']);
Route::get('/articles', [HomeController::class, 'Article']);
Route::get('/articles/{slug}', [HomeController::class, 'ArticleDetail']);
Route::get('/faq', [HomeController::class, 'Faq']);
Route::get('/help', [HomeController::class, 'Help']);
Route::get('/search', [HomeController::class, 'Search']);
Route::get('/vacancies',[HomeController::class,'Career']);
Route::get('/vacancies/{slug}',[HomeController::class,'CareerDetail']);


//Application sending route
Route::post('/application/send', [HomeController::class, 'sendApplication']);
//Contact Sending route
Route::post('/contact/send', [HomeController::class, 'sendContact']);

//Auth Routes
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);
Route::post('/password/email', [AuthController::class,'sendPasswordResetLinkEmail'])->middleware('throttle:5,1');
Route::post('/password/reset', [AuthController::class,'resetPassword']);

//Social Login Routes
Route::get('/login/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [AuthController::class,'handleProviderCallback']);




//Routes for authenticated users
Route::middleware(['auth:sanctum'])->group(function () {

    //Profile related routes
    Route::get('/user/dashboard', [UserController::class, 'Dashboard']);
    Route::post('/user/profile/edit/{user_id}', [UserController::class, 'editProfile']);
    Route::post('/user/password/{user_id}', [UserController::class, 'changePassword']);
    Route::post('/user/logout/{user_id}', [AuthController::class, 'Logout']);

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
});


//Admin Routes for Admin
Route::middleware(['auth:sanctum', 'authAdmin'])->group(function () {

    // Route::group(['middleware' => ['role_or_permission:super-admin|author|publisher']], function () {});

    Route::post('/admin/article/store', [ArticleController::class, 'store']);
    Route::get('/admin/article', [ArticleController::class, 'index']);
    Route::post('/admin/article/edit/{id}', [ArticleController::class, 'update']);
    Route::post('/admin/article/destroy/{id}', [ArticleController::class, 'destroy']);

    Route::get('/admin/adpack', [AdpackController::class, 'index']);
    Route::post('/admin/adpack/store', [AdpackController::class, 'store']);
    Route::post('/admin/adpack/edit/{id}', [AdpackController::class, 'update']);
    Route::post('/admin/adpack/destroy/{id}', [AdpackController::class, 'destroy']);

    Route::get('/admin/advertisement', [AdvertisementController::class, 'index']);
    Route::post('/admin/advertisement/store', [AdvertisementController::class, 'store']);
    Route::post('/admin/advertisement/edit/{id}', [AdvertisementController::class, 'update']);
    Route::post('/admin/advertisement/destroy/{id}', [AdvertisementController::class, 'destroy']);

    Route::get('/admin/faq', [FaqController::class, 'index']);
    Route::post('/admin/faq/store', [FaqController::class, 'store']);
    Route::post('/admin/faq/edit/{id}', [FaqController::class, 'update']);
    Route::post('/admin/faq/destroy/{id}', [FaqController::class, 'destroy']);

    Route::get('/admin/customfield', [CustomFieldController::class, 'index']);
    Route::post('/admin/customfield/store', [CustomFieldController::class, 'store']);
    Route::post('/admin/customfield/edit/{id}', [CustomFieldController::class, 'update']);
    Route::post('/admin/customfield/destroy/{id}', [CustomFieldController::class, 'destroy']);

    Route::get('/admin/category', [CategoryController::class, 'index']);
    Route::post('/admin/category/store', [CategoryController::class, 'store']);
    Route::post('/admin/category/edit/{id}', [CategoryController::class, 'update']);
    Route::post('/admin/category/destroy/{id}', [CategoryController::class, 'destroy']);


    Route::get('/admin/location', [LocationController::class, 'index']);
    Route::post('/admin/location/store', [LocationController::class, 'store']);
    Route::post('/admin/location/edit/{id}', [LocationController::class, 'update']);
    Route::post('/admin/location/destroy/{id}', [LocationController::class, 'destroy']);

    Route::get('/admin/menu', [MenuController::class, 'index']);
    Route::post('/admin/menu/store', [MenuController::class, 'store']);
    Route::post('/admin/menu/edit/{id}', [MenuController::class, 'update']);
    Route::post('/admin/menu/destroy/{id}', [MenuController::class, 'destroy']);

    Route::get('/admin/page', [PageController::class, 'index']);
    Route::post('/admin/page/store', [PageController::class, 'store']);
    Route::post('/admin/page/edit/{id}', [PageController::class, 'update']);
    Route::post('/admin/page/destroy/{id}', [PageController::class, 'destroy']);

    Route::get('/admin/popup', [PopupController::class, 'index']);
    Route::post('/admin/popup/store', [PopupController::class, 'store']);
    Route::post('/admin/popup/edit/{id}', [PopupController::class, 'update']);
    Route::post('/admin/popup/destroy/{id}', [PopupController::class, 'destroy']);

    Route::get('/admin/slider', [SliderController::class, 'index']);
    Route::post('/admin/slider/store', [SliderController::class, 'store']);
    Route::post('/admin/slider/edit/{id}', [SliderController::class, 'update']);
    Route::post('/admin/slider/destroy/{id}', [SliderController::class, 'destroy']);

    Route::get('/admin/vacancy', [VacancyController::class, 'index']);
    Route::post('/admin/vacancy/store', [VacancyController::class, 'store']);
    Route::post('/admin/vacancy/edit/{id}', [VacancyController::class, 'update']);
    Route::post('/admin/vacancy/destroy/{id}', [VacancyController::class, 'destroy']);

    Route::get('/admin/role', [RoleController::class, 'index']);
    Route::post('/admin/role/store', [RoleController::class, 'store']);
    Route::post('/admin/role/edit/{id}', [RoleController::class, 'update']);
    Route::post('/admin/role/destroy/{id}', [RoleController::class, 'destroy']);

    Route::get('/admin/permission', [PermissionController::class, 'index']);
    Route::post('/admin/permission/store', [PermissionController::class, 'store']);
    Route::post('/admin/permission/edit/{id}', [PermissionController::class, 'update']);
    Route::post('/admin/permission/destroy/{id}', [PermissionController::class, 'destroy']);

    Route::get('/admin/user', [AdminController::class, 'index']);
    Route::post('/admin/user/store', [AdminController::class, 'store']);
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'update']);
    Route::post('/admin/user/destroy/{id}', [AdminController::class, 'destroy']);

    Route::get('/admin/setup', [SetupController::class, 'index']);
    Route::post('/admin/setup/edit', [SetupController::class, 'update']);


    Route::get('/admin/contact', [ContactController::class, 'index']);
    Route::get('/admin/contact/show/{id}', [ContactController::class, 'show']);
    Route::post('/admin/contact/destroy/{id}', [ContactController::class, 'destroy']);

    Route::get('/admin/application', [ApplicationController::class, 'index']);
    Route::get('/admin/application/show/{id}', [ApplicationController::class, 'show']);
    Route::post('/admin/application/destroy/{id}', [ApplicationController::class, 'destroy']);

    Route::get('/admin/post/', [AdminPostController::class, 'index']);
    Route::post('/admin/post/store', [AdminPostController::class, 'store']);
    Route::post('/admin/post/edit/{id}', [AdminPostController::class, 'update']);
    Route::post('/admin/post/destroy/{id}', [AdminPostController::class, 'destroy']);

    Route::get('/admin/postmeta/', [PostMetaController::class, 'index']);
    Route::post('/admin/postmeta/store', [PostMetaController::class, 'store']);
    Route::post('/admin/postmeta/edit/{id}', [PostMetaController::class, 'update']);
    Route::post('/admin/postmeta/destroy/{id}', [PostMetaController::class, 'destroy']);
});
