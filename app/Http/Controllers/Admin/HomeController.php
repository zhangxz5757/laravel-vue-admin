<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\MenuService;
use App\Models\SysRoleMenuModel;
use App\Models\SysRoleModel;
use App\Models\SysMenuModel;
use App\Models\SysUserRoleModel;
use App\Utils\TreeUtils;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends AdminBaseController
{
    public function indexAction()
    {
        $admin = Auth::guard('admin')->user();

        $roleIds = SysUserRoleModel::query()->where('user_id', $admin->id)->pluck('role_id');

        $menuIds = SysRoleMenuModel::query()->whereIn('role_id', $roleIds)->pluck('menu_id');
        $menuList = SysMenuModel::query()
//            ->whereIn('type', [0, 2])
            ->when(!$this->isSuper(), fn ($q) => $q->whereIn('id', $menuIds))
            ->get()
            ->toArray();

        return $this->jsonSuccess([
            'menu' => $menuList
        ]);
    }

    /**
     * 初始化
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initAction()
    {
        $admin = Auth::guard('admin')->user();

        $roleIds = SysUserRoleModel::query()->where('user_id', $admin->id)->pluck('role_id');

        $menuIds = SysRoleMenuModel::query()->whereIn('role_id', $roleIds)->pluck('menu_id');
        $menuList = SysMenuModel::query()
//            ->whereIn('type', [0, 2])
            ->when(!$this->isSuper(), fn ($q) => $q->whereIn('id', $menuIds))
            ->get()
            ->toArray();

        $menuInfo = TreeUtils::makeTree($menuList, [
            'children_key' => 'children'
        ]);

        foreach ($menuInfo as $key => & $item) {
            if (empty($item['child'])) {
                continue;
            }
            foreach ($item['child'] as $_k => & $_i) {
                if (empty($_i['child'])) {
                    continue;
                }
                foreach ($_i['child'] as & $_it) {
                    unset($_it['child']);
                }
            }
        }

        return response()->json([
            'clearInfo' => [
                'clearUrl' => '/admin/clean'
            ],
            'homeInfo' => [
                'title' => '平台概况',
                'icon' => 'fa fa-home',
                'href' => '/admin/dashboard/index'
            ],
            'logoInfo' => [
                'title' => '运营管理平台',
                'image' => '/images/logo.png',
                "href" => ""
            ],
            'menuInfo' => $menuInfo
        ]);
    }

    /**
     * 清理缓存
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cleanAction()
    {

        Artisan::call('cache:clear');

        Cache::forget("manager:menu");
        return response()->json([
            "code"=> 1,
            "msg" => "服务端清理缓存成功"
        ]);
    }
}
