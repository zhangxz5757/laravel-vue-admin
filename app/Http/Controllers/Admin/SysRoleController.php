<?php

namespace App\Http\Controllers\Admin;

use App\Models\SysRoleMenuModel;
use App\Models\SysRoleModel;
use App\Models\SysMenuModel;
use App\Utils\TreeUtils;
use Illuminate\Http\Request;

class SysRoleController extends AdminBaseController
{
    /**
     * 角色列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $paginate = SysRoleModel::query()
            ->when($k = $request->get('keyword'), fn ($q) => $q->where('title', 'like', "%{$k}%"))
            ->when($request->get('status') != '', fn ($q) => $q->where('status', $request->get('status')))
            ->paginate($request->get('per_page'));

        return $this->jsonSuccess($paginate);
    }

    /**
     * 所有角色列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allAction()
    {
        $paginate = SysRoleModel::query()
            ->select(['id', 'title'])
            ->get();

        return $this->jsonSuccess($paginate);
    }

    /**
     * 添加/编辑角色
     */
    public function storeAction(Request $request)
    {
        $id = $request->get('id');

        $param = $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);
        $vo = SysRoleModel::query()->updateOrCreate([
            'id' => $id
        ], $param);
        $menuIds = $request->get('menu_ids', []);
        // 删除原有权限
        SysRoleMenuModel::query()->where('role_id', $vo->id)->delete();
        foreach ($menuIds as $menuId) {
            SysRoleMenuModel::query()->create([
                'role_id' => $vo->id,
                'menu_id' => $menuId
            ]);
        }
        return $this->jsonSuccess();
    }

    /**
     * 删除分组
     */
    public function deleteAction(Request $request)
    {
        $id = $this->getParamWithThrow('id');

//        $hasUser = SysUserModel::query()->where('group_id', $id)->first();
//
//        if ($hasUser) {
//            return $this->jsonFail(422, '当前角色下有人员，请先删除后再操作！');
//        }
        SysRoleModel::where('id', $id)->delete();
        return $this->jsonSuccess();
    }

    /**
     * 角色详情
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function infoAction(Request $request)
    {
        $id = $request->get('id');

        $role = SysRoleModel::query()->find($id);
        if (!$role) {
            return $this->jsonFail(422, '角色不存在');
        }
        $role->menu_ids = SysRoleMenuModel::query()->where('role_id', $id)->pluck('menu_id');

        return $this->jsonSuccess($role);
    }
}
