<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SysDictTypeModel as ThisTable;
use Illuminate\Http\Request;

class SysDictTypeController extends BaseController
{

    private $field = [
        'id', 'title', 'status', 'sort_id'
    ];

    private $validate = [
        'title' => 'required',
        'status' => 'required',
        'sort_id' => 'required',
    ];

    private $message = [];

    /**
     * 列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $list = ThisTable::query()
            ->when($k = $request->get('keyword'), fn ($q) => $q->where('title', 'like', "%{$k}%"))
            ->orderByDesc('sort_id')
            ->select($this->field)
            ->get();
        return $this->jsonSuccess([
            'list' => $list
        ]);
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

        ThisTable::query()->updateOrCreate([
            'id' => $id
        ], $vData);

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
        $vo = ThisTable::query()->find($id);
        if (!$vo) {
            return $this->jsonFail(422);
        }
        $vo->delete();
        return $this->jsonSuccess();
    }

    /**
     * 查看详情
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function infoAction(Request $request)
    {
        $id = $this->getParamWithThrow('id');
        $vo = ThisTable::query()->find($id);
        if (!$vo) {
            return $this->jsonFail(422);
        }
        return $this->jsonSuccess($vo);
    }
}