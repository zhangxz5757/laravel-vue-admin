<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\DictTypeController;
use App\Http\Controllers\Admin\DictDataController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SysUserController;
use App\Http\Controllers\Admin\SysRoleController;
use App\Http\Controllers\Admin\SysMenuController;
use App\Http\Controllers\Admin\SysDepartmentController;
use App\Http\Controllers\Admin\SysDictTypeController;
use Illuminate\Support\Facades\Route;

// 登录
Route::middleware('sys.log')->post('/login', [AuthController::class, 'loginAction']);
Route::middleware('sys.log')->any('/image-code', [AuthController::class, 'loginCode']);


// 公共
Route::middleware(['auth.admin', 'sys.log'])->group(function () {
    // 登出
    Route::get('/logout', [AuthController::class, 'logoutAction']);
    // 图片上传
    Route::any('/uploads', [CommonController::class, 'uploadAction']);
    // 菜单
    // Route::any('/init', [HomeController::class, 'initAction']);
    // 清理缓存
    Route::any('/clean', [HomeController::class, 'cleanAction']);
    // 默认首页接口
    Route::any('/home/index', [HomeController::class, 'indexAction']);
});

// 系统管理相关接口
Route::middleware(['auth.admin', 'sys.log'])->group(function () {
    Route::any('/auth/{action}', [AuthController::class, 'action']);
    // Route::any('/home/{action}', [HomeController::class, 'action']);
    // 管理员管理
    Route::any('/sys_user/{action}', [SysUserController::class, 'action']);
    // 角色管理
    Route::any('/sys_role/{action}', [SysRoleController::class, 'action']);
    // 菜单管理
    Route::any('/sys_menu/{action}', [SysMenuController::class, 'action']);
    // 部门管理
    Route::any('/sys_dept/{action}', [SysDepartmentController::class, 'action']);


    // 字典管理
    Route::any('/dict_type/{action}', [DictTypeController::class, 'action']);
    Route::any('/dict_data/{action}', [DictDataController::class, 'action']);
});
