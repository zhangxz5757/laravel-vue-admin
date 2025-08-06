<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController extends Controller
{
    public function getParamWithThrow($key, $msg = '')
    {
        $value = request()->get($key);
        if (is_null($value)) {
            throw new ApiException($msg);
        }
        return $value;
    }

    /**
     * json成功方法
     *
     * @param $data
     * @return JsonResponse
     */
    protected function jsonSuccess($data = [])
    {
        $response = new JsonResponse();
        $response->setData([
            'data' => $data ?: new \stdClass(),
            'msg' => '成功',
            'code' => 200
        ]);
        return $response;
    }

    /**
     * json失败方法
     *
     * @param $code
     * @param $msg
     * @param $data
     * @return JsonResponse
     */
    protected function jsonFail($code = 422, $msg = '', $data = [])
    {
        $response = new JsonResponse();
        $response->setData([
            'data' => $data ?: new \stdClass(),
            'msg' => $msg,
            'code' => $code
        ]);
        return $response;
    }


    /**
     * 访问路由
     *
     * @param $action
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function action($action, Request $request)
    {
        $method = $action . 'Action';
        if (method_exists(get_called_class(), $method)) {
            return $this->$method($request);
        }
        throw new NotFoundHttpException("访问的方法名称需要已Action结尾");
    }
}
