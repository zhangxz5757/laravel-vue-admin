<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::any('/mini/login', [\App\Http\Controllers\Api\MiniProjectController::class, 'login']);
Route::any('/mini/bind-mobile', [\App\Http\Controllers\Api\MiniProjectController::class, 'encrypt']);
Route::any('/auth/{action}', [\App\Http\Controllers\Api\AuthController::class, 'action']);


Route::any('/sms/send', [\App\Http\Controllers\Api\CommonController::class, 'sendCode']);
Route::any('/sms/verify', [\App\Http\Controllers\Api\CommonController::class, 'verifyAction']);
Route::any('/area/index', [\App\Http\Controllers\Api\CommonController::class, 'areaAction']);
Route::any('/agreement/info', [\App\Http\Controllers\Api\CommonController::class, 'agreement']);
Route::any('/agreement/index', [\App\Http\Controllers\Api\CommonController::class, 'agreementIndex']);

Route::any('/notice/{action}', [\App\Http\Controllers\Api\NoticeController::class, 'action']);
Route::any('/news/{action}', [\App\Http\Controllers\Api\NewsController::class, 'action']);
Route::any('/platform/{action}', [\App\Http\Controllers\Api\PlatformController::class, 'action']);
Route::any('/auction_news/{action}', [\App\Http\Controllers\Api\AuctionNewsController::class, 'action']);
Route::any('/vip_service/{action}', [\App\Http\Controllers\Api\VipController::class, 'action']);
Route::any('/support/{action}', [\App\Http\Controllers\Api\SupportController::class, 'action']);

# 上传
Route::any('/upload', [\App\Http\Controllers\Api\CommonController::class, 'uploadAction']);
Route::any('/home', [\App\Http\Controllers\Api\HomeController::class, 'indexAction']);
Route::any('/industry', [\App\Http\Controllers\Api\HomeController::class, 'industryAction']);
Route::any('/area', [\App\Http\Controllers\Api\HomeController::class, 'areaAction']);
Route::any('/home/province', [\App\Http\Controllers\Api\HomeController::class, 'provinceAction']);
Route::any('/corp/register', [\App\Http\Controllers\Api\AuthController::class, 'corpRegAction']);
Route::any('/auction/{action}', [\App\Http\Controllers\Api\AuctionController::class, 'action']);

Route::any('/goods/home', [\App\Http\Controllers\Api\GoodsController::class, 'homeAction']);
Route::any('/goods/index', [\App\Http\Controllers\Api\GoodsController::class, 'indexAction']);
Route::any('/goods/info', [\App\Http\Controllers\Api\GoodsController::class, 'infoAction']);
Route::any('/assets/index', [\App\Http\Controllers\Api\AssetsController::class, 'indexAction']);
Route::any('/assets/info', [\App\Http\Controllers\Api\AssetsController::class, 'infoAction']);
Route::any('/assets/important', [\App\Http\Controllers\Api\AssetsController::class, 'importantAction']);
Route::any('/assets/type', [\App\Http\Controllers\Api\AssetsController::class, 'typeAction']);
Route::any('/assets/status', [\App\Http\Controllers\Api\AssetsController::class, 'statusAction']);
Route::any('/data-center/{action}', [\App\Http\Controllers\Api\DataController::class, 'action']);

Route::any('/auction_record/{action}', [\App\Http\Controllers\Api\AuctionRecordController::class, 'action']);

Route::middleware(['auth.api'])->group(function () {
    # 意见反馈
    Route::any('/report/{action}', [\App\Http\Controllers\Api\ReportController::class, 'action']);

    Route::any('/address/{action}', [\App\Http\Controllers\Api\AddressController::class, 'action']);

    // 商品收藏/取消收藏
    Route::post('/goods/collect', [\App\Http\Controllers\Api\GoodsController::class, 'collectAction']);
    Route::get('/collect/index', [\App\Http\Controllers\Api\CollectController::class, 'indexAction']);

    // 订单
    Route::post('/order/store', [\App\Http\Controllers\Api\OrderController::class, 'storeAction']);
    Route::get('/order/index', [\App\Http\Controllers\Api\OrderController::class, 'indexAction']);
    Route::get('/order/info', [\App\Http\Controllers\Api\OrderController::class, 'infoAction']);
    Route::get('/order/bank', [\App\Http\Controllers\Api\OrderController::class, 'bankAction']);
    Route::post('/order/voucher', [\App\Http\Controllers\Api\OrderController::class, 'voucherAction']);


    Route::any('/auction_apply/{action}', [\App\Http\Controllers\Api\AuctionApplyController::class, 'action']);

    // 修改手机号
    Route::any('/user/change-mobile', [\App\Http\Controllers\Api\AuthController::class, 'changeMobileAction']);
    Route::any('/user/change-password', [\App\Http\Controllers\Api\AuthController::class, 'resetPasswordAction']);

    //信息发布，我要买卖
    Route::any('/demand/store', [\App\Http\Controllers\Api\DemandController::class, 'storeAction']);
    Route::any('/demand/index', [\App\Http\Controllers\Api\DemandController::class, 'indexAction']);
});

