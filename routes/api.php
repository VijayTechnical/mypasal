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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return Auth::user();
// });

//Routes with no middleware used in the front end
Route::get('/categories', [HomeController::class, 'Category'])->name('category');
Route::get('/locations', [HomeController::class, 'Location'])->name('location');
Route::get('/featured_posts', [HomeController::class, 'FeaturedPost'])->name('featured_posts');
Route::get('/trending_posts', [HomeController::class, 'TrendingPost'])->name('trending_posts');
Route::get('/recent_posts', [HomeController::class, 'RecentPost'])->name('recent_posts');
Route::get('/sliders', [HomeController::class, 'Slider'])->name('sliders');
Route::get('/popup', [HomeController::class, 'Popup'])->name('popup');
Route::get('/setup', [HomeController::class, 'Setup'])->name('setup');
Route::get('/categories/{slug}', [HomeController::class, 'CategoryPost'])->name('category.posts');
Route::get('/post/detail/{slug}', [HomeController::class, 'PostDetail'])->name('post.detail');
Route::get('/articles', [HomeController::class, 'Article'])->name('articles');
Route::get('/articles/{slug}', [HomeController::class, 'ArticleDetail'])->name('articles.details');
Route::get('/faq', [HomeController::class, 'Faq'])->name('faq');
Route::get('/help', [HomeController::class, 'Help'])->name('help');
Route::get('/search', [HomeController::class, 'Search'])->name('search');


//Application sending route
Route::post('/application/send', [HomeController::class, 'sendApplication']);
//Contact Sending route
Route::post('/contact/send', [HomeController::class, 'sendContact']);

//Auth Routes
Route::post('/login', [AuthController::class, 'Login'])->name('login');
Route::post('/register', [AuthController::class, 'Register'])->name('register');
Route::post('/password/email', [AuthController::class,'sendPasswordResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
Route::post('/password/reset', [AuthController::class,'resetPassword'])->name('password.reset');




//Routes for authenticated users
Route::middleware(['auth:sanctum'])->group(function () {

    //Profile related routes
    Route::get('/user/dashboard', [UserController::class, 'Dashboard'])->name('dashboard');
    Route::post('/user/profile/edit/{user_id}', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/user/password/{user_id}', [UserController::class, 'changePassword'])->name('password.change');
    Route::post('/user/logout/{user_id}', [AuthController::class, 'Logout'])->name('logout');

    //User Pack related routes
    Route::get('/user/userpack/', [UserPackController::class, 'index'])->name('userpack');
    Route::post('/user/userpack/store', [UserPackController::class, 'store'])->name('userpack.store');
    Route::get('/user/userpack/show/{id}', [UserPackController::class, 'show'])->name('userpack.show');
    Route::post('/user/userpack/edit/{id}', [UserPackController::class, 'update'])->name('userpack.edit');
    Route::post('/user/userpack/destroy/{id}', [UserPackController::class, 'destroy'])->name('userpack.delete');

    //Post related routes
    Route::get('/user/post/', [PostController::class, 'index'])->name('post');
    Route::post('/user/post/store', [PostController::class, 'store'])->name('post.store');
    Route::post('/user/post/edit/{id}', [PostController::class, 'update'])->name('post.edit');
    Route::post('/user/post/destroy/{id}', [PostController::class, 'destroy'])->name('post.delete');

    //Message Routes
    Route::get('/user/conversation/{id}', [ContactsController::class, 'getMessagesFor']);
    Route::post('/user/conversation/send', [ContactsController::class, 'sendMessage']);

    //Routes to store comments
    Route::get('/post/comment/{id?}', [CommentController::class, 'index'])->name('comment');
    Route::post('/post/comment/store', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/post/comment/edit/{id}', [CommentController::class, 'update'])->name('comment.edit');
    Route::post('/post/comment/destroy/{id}', [CommentController::class, 'destroy'])->name('comment.delete');

    //Logout
    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');
});


//Admin Routes for Admin
Route::middleware(['auth:sanctum', 'authAdmin'])->group(function () {

    // Route::group(['middleware' => ['role_or_permission:super-admin|author|publisher']], function () {});

    Route::post('/admin/article/store', [ArticleController::class, 'store'])->name('article.store');
    Route::get('/admin/article', [ArticleController::class, 'index'])->name('article');
    Route::post('/admin/article/edit/{id}', [ArticleController::class, 'update'])->name('article.edit');
    Route::post('/admin/article/destroy/{id}', [ArticleController::class, 'destroy'])->name('article.delete');

    Route::get('/admin/adpack', [AdpackController::class, 'index'])->name('adpack');
    Route::post('/admin/adpack/store', [AdpackController::class, 'store'])->name('adpack.store');
    Route::post('/admin/adpack/edit/{id}', [AdpackController::class, 'update'])->name('adpack.edit');
    Route::post('/admin/adpack/destroy/{id}', [AdpackController::class, 'destroy'])->name('adpack.delete');

    Route::get('/admin/advertisement', [AdvertisementController::class, 'index'])->name('advertisement');
    Route::post('/admin/advertisement/store', [AdvertisementController::class, 'store'])->name('advertisement.store');
    Route::post('/admin/advertisement/edit/{id}', [AdvertisementController::class, 'update'])->name('advertisement.edit');
    Route::post('/admin/advertisement/destroy/{id}', [AdvertisementController::class, 'destroy'])->name('advertisement.delete');

    Route::get('/admin/faq', [FaqController::class, 'index'])->name('faq');
    Route::post('/admin/faq/store', [FaqController::class, 'store'])->name('faq.store');
    Route::post('/admin/faq/edit/{id}', [FaqController::class, 'update'])->name('faq.edit');
    Route::post('/admin/faq/destroy/{id}', [FaqController::class, 'destroy'])->name('faq.delete');

    Route::get('/admin/customfield', [CustomFieldController::class, 'index'])->name('customfield');
    Route::post('/admin/customfield/store', [CustomFieldController::class, 'store'])->name('customfield.store');
    Route::post('/admin/customfield/edit/{id}', [CustomFieldController::class, 'update'])->name('customfield.edit');
    Route::post('/admin/customfield/destroy/{id}', [CustomFieldController::class, 'destroy'])->name('customfield.delete');

    Route::get('/admin/category', [CategoryController::class, 'index'])->name('category');
    Route::post('/admin/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/admin/category/edit/{id}', [CategoryController::class, 'update'])->name('category.edit');
    Route::post('/admin/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.delete');


    Route::get('/admin/location', [LocationController::class, 'index'])->name('location');
    Route::post('/admin/location/store', [LocationController::class, 'store'])->name('location.store');
    Route::post('/admin/location/edit/{id}', [LocationController::class, 'update'])->name('location.edit');
    Route::post('/admin/location/destroy/{id}', [LocationController::class, 'destroy'])->name('location.delete');

    Route::get('/admin/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/admin/menu/store', [MenuController::class, 'store'])->name('menu.store');
    Route::post('/admin/menu/edit/{id}', [MenuController::class, 'update'])->name('menu.edit');
    Route::post('/admin/menu/destroy/{id}', [MenuController::class, 'destroy'])->name('menu.delete');

    Route::get('/admin/page', [PageController::class, 'index'])->name('page');
    Route::post('/admin/page/store', [PageController::class, 'store'])->name('page.store');
    Route::post('/admin/page/edit/{id}', [PageController::class, 'update'])->name('page.edit');
    Route::post('/admin/page/destroy/{id}', [PageController::class, 'destroy'])->name('page.delete');

    Route::get('/admin/popup', [PopupController::class, 'index'])->name('popup');
    Route::post('/admin/popup/store', [PopupController::class, 'store'])->name('popup.store');
    Route::post('/admin/popup/edit/{id}', [PopupController::class, 'update'])->name('popup.edit');
    Route::post('/admin/popup/destroy/{id}', [PopupController::class, 'destroy'])->name('popup.delete');

    Route::get('/admin/slider', [SliderController::class, 'index'])->name('slider');
    Route::post('/admin/slider/store', [SliderController::class, 'store'])->name('slider.store');
    Route::post('/admin/slider/edit/{id}', [SliderController::class, 'update'])->name('slider.edit');
    Route::post('/admin/slider/destroy/{id}', [SliderController::class, 'destroy'])->name('slider.delete');

    Route::get('/admin/vacancy', [VacancyController::class, 'index'])->name('vacancy');
    Route::post('/admin/vacancy/store', [VacancyController::class, 'store'])->name('vacancy.store');
    Route::post('/admin/vacancy/edit/{id}', [VacancyController::class, 'update'])->name('vacancy.edit');
    Route::post('/admin/vacancy/destroy/{id}', [VacancyController::class, 'destroy'])->name('vacancy.delete');

    Route::get('/admin/role', [RoleController::class, 'index'])->name('role');
    Route::post('/admin/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::post('/admin/role/edit/{id}', [RoleController::class, 'update'])->name('role.edit');
    Route::post('/admin/role/destroy/{id}', [RoleController::class, 'destroy'])->name('role.delete');

    Route::get('/admin/permission', [PermissionController::class, 'index'])->name('permission');
    Route::post('/admin/permission/store', [PermissionController::class, 'store'])->name('permission.store');
    Route::post('/admin/permission/edit/{id}', [PermissionController::class, 'update'])->name('permission.edit');
    Route::post('/admin/permission/destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.delete');

    Route::get('/admin/user', [AdminController::class, 'index'])->name('user');
    Route::post('/admin/user/store', [AdminController::class, 'store'])->name('user.store');
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'update'])->name('user.edit');
    Route::post('/admin/user/destroy/{id}', [AdminController::class, 'destroy'])->name('user.delete');

    Route::get('/admin/setup', [SetupController::class, 'index'])->name('setup');
    Route::post('/admin/setup/edit', [SetupController::class, 'update'])->name('setup.edit');


    Route::get('/admin/contact', [ContactController::class, 'index'])->name('contact');
    Route::get('/admin/contact/show/{id}', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/admin/contact/destroy/{id}', [ContactController::class, 'destroy'])->name('contact.delete');

    Route::get('/admin/application', [ApplicationController::class, 'index'])->name('application');
    Route::get('/admin/application/show/{id}', [ApplicationController::class, 'show'])->name('application.show');
    Route::post('/admin/application/destroy/{id}', [ApplicationController::class, 'destroy'])->name('application.delete');

    Route::get('/admin/post/', [AdminPostController::class, 'index'])->name('post');
    Route::post('/admin/post/store', [AdminPostController::class, 'store'])->name('post.store');
    Route::post('/admin/post/edit/{id}', [AdminPostController::class, 'update'])->name('post.edit');
    Route::post('/admin/post/destroy/{id}', [AdminPostController::class, 'destroy'])->name('post.delete');

    Route::get('/admin/postmeta/', [PostMetaController::class, 'index'])->name('postmeta');
    Route::post('/admin/postmeta/store', [PostMetaController::class, 'store'])->name('postmeta.store');
    Route::post('/admin/postmeta/edit/{id}', [PostMetaController::class, 'update'])->name('postmeta.edit');
    Route::post('/admin/postmeta/destroy/{id}', [PostMetaController::class, 'destroy'])->name('postmeta.delete');
});
