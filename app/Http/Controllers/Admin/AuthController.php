<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SysUserModel;
use App\Utils\CodeImageGenerateUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * 用户信息
 */
class AuthController extends BaseController
{
    /**
     * 管理员登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAction(Request $request)
    {
        $vData = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'key'      => 'required',
            'code'     => 'required',
        ], [
            'username' => '请输入账号',
            'password' => '请输入密码',
            'code'     => '请输入验证码',
        ]);
        if (!captcha_api_check($vData['code'], $vData['key'])) {
            return $this->jsonFail(422, '验证码验证失败');
        }
        $admin = SysUserModel::query()->where('username', $request->get('username'))->first();

        if (!$admin) {
            return $this->jsonFail(422, '账号或密码错误!');
        }

        if ($admin->status != 1) {
            return $this->jsonFail(422, '账号已禁用');
        }

        if (!Hash::check($request->get('password'), $admin->password)) {
            return $this->jsonFail(422, '账号或密码错误!!');
        }
        // TODO 账号密码错误锁定

        $token = Auth::guard('admin')->login($admin);

        return $this->jsonSuccess([
            'token' => 'Bearer ' . $token
        ]);
    }

    /**
     *
     *
     * @return void
     */
    public function loginCode()
    {
        return $this->jsonSuccess(app('captcha')->create('default', true));
    }


    /**
     * 登出
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logoutAction()
    {
        Auth::guard('admin')->logout();
        // 清理菜单缓存
        Cache::forget('menu:' . Auth::guard('admin')->id());
        return redirect(route('admin.login'));
    }

    /**
     * 管理员基本信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function infoAction()
    {
        $user = Auth::guard('admin')->user();
        return $this->jsonSuccess($user);
    }
}
