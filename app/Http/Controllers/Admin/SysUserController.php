<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\CommonService;
use App\Models\SysRoleModel;
use App\Models\SysUserModel;
use App\Models\SysDepartmentModel;
use App\Models\SysUserRoleModel;
use App\Utils\TreeUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class SysUserController extends AdminBaseController
{

    private $validate = [
        'nickname'      => 'required',
        'username'      => 'required',
        'password'      => 'nullable',
        'department_id' => 'nullable',
        'status'        => 'required',
        'mobile'        => 'nullable',
    ];

    private $message = [];

    /**
     * 管理员列表
     */
    public function indexAction(Request $request)
    {
        $paginate = SysUserModel::query()
            ->leftJoin('sys_department', 'department_id', '=', 'sys_department.id')
            ->when($k = $request->get('keyword'), fn($q) => $q->where('sys_user.username', 'like', "%{$k}%"))
            ->when($request->get('status') != '', fn($q) => $q->where('sys_user.status', $request->get('status')))
            ->select([
                'sys_user.*',
                'sys_department.title as dept_title'
            ])
            ->paginate($request->get('per_page'));

        return $this->jsonSuccess($paginate);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function infoAction(Request $request)
    {
        $id = $this->getParamWithThrow('id');
        $vo = SysUserModel::query()->find($id);

        if (!$vo) {
            return $this->jsonFail(422, '参数错误');
        }
        $vo->role_ids = SysUserRoleModel::query()->where('user_id', $id)->pluck('role_id');
        return $this->jsonSuccess($vo);
    }

    /**
     * 保存
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAction(Request $request)
    {
        $id = $request->get('id');
        $vData = $this->validate($request, $this->validate, $this->message);

        // 判断账号是否存在
        $hasUser = SysUserModel::query()
            ->where('username', $vData['username'])
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->first();
        if ($hasUser) {
            return $this->jsonFail(422, '已存在相同账号');
        }

        // 判断手机号是否存在
        $hasUser = SysUserModel::query()
            ->where('mobile', $vData['mobile'])
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->first();
        if ($hasUser) {
            return $this->jsonFail(422, '已存在相同手机号');
        }

        // 密码处理
        if (!empty($vData['password'])) {
            $vData['password'] = Hash::make($vData['password']);
        } else {
            unset($vData['password']);
        }

        $vo = SysUserModel::query()->updateOrCreate([
            'id' => $id
        ], $vData);

        // 用户角色处理
        $id && SysUserRoleModel::query()->where('user_id', $vo->id)->delete();
        $roleIds = $request->get('role_ids', []);
        foreach ($roleIds as $roleId) {
            SysUserRoleModel::query()->create([
                'user_id' => $vo->id,
                'role_id' => $roleId
            ]);
        }

        return $this->jsonSuccess();
    }

    /**
     * 删除
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $this->getParamWithThrow('id');
        $vo = SysUserModel::query()->find($id);
        if (!$vo) {
            return $this->jsonFail(422);
        }
        $vo->delete();
        return $this->jsonSuccess();
    }

    public function allAction(Request $request)
    {
        $list = SysUserModel::query()->orderBy('username')->get(['id', 'username']);
        return $this->jsonSuccess([
            'list' => $list
        ]);
    }


    /**
     * 当前用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myAction()
    {
        $item = SysUserModel::query()
            ->leftJoin('sys_department', 'dept_id', '=', 'sys_department.id')
            ->where('manager.id', Auth::guard('admin')->id())
            ->select([
                'sys_user.*', 'sys_user.created_at as create_time',
                'sys_dept.title as dept_title'
            ])
            ->first();

        // group_title
        $groupList = SysRoleModel::query()->get(['id', 'title']);
        $groupList = array_column($groupList->toArray(), 'title', 'id');

        $groupIds = explode('|', $item->group_id);
        $groupTitle = [];
        foreach ($groupIds as & $groupId) {
            $groupId = (int)$groupId;
            $groupTitle[] = $groupList[$groupId] ?? '';
        }
        $item->group_id = $groupIds;

        $item->group_title = implode(',', array_filter($groupTitle));

        return $this->jsonSuccess($item);
    }

    /**
     * 保存个人信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveMyAction(Request $request)
    {
        $vData = $this->validate($request, [
            'nickname' => 'required',
            'mobile'   => 'required',
            'password' => 'nullable',
        ]);
        $vo = SysUserModel::query()->find(Auth::guard('admin')->id());
        if (!empty($vData['password'])) {
            $vData['password'] = Hash::make($vData['password']);
        } else {
            unset($vData['password']);
        }
        $vo->update($vData);
        return $this->jsonSuccess();
    }
}
