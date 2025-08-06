<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SysDictTypeModel as ThisTable;
use Illuminate\Http\Request;

class DictController extends BaseController
{

    private $validate = [
        'title' => 'required',
        'alias' => 'required',
        'sort_id' => 'required',
        'status' => 'required',
    ];

    private $message = [];


    /*
     * 列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $paginate = ThisTable::query()
            ->when($k = $request->get('keyword'), fn ($q) => $q->where('title', 'like', "%{$k}%"))
            ->when($k = $request->get('alias'), fn ($q) => $q->where('alias', 'like', "%{$k}%"))
            ->orderBy('sort_id')
            ->select([
                'id', 'title', 'alias', 'sort_id', 'status', 'created_at as create_time'
            ])
            ->paginate($request->get('per_page'));

        return $this->jsonSuccess($paginate);
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