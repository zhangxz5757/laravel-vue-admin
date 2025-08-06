<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SysDictDataModel as ThisTable;
use Illuminate\Http\Request;

class DictDataController extends BaseController
{

    private $validate = [
        'alias' => 'required',
        'label' => 'required',
        'value' => 'required',
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
            ->where('alias', $request->get('alias'))
            ->orderBy('sort_id')
            ->select([
                'id', 'label', 'value', 'alias', 'sort_id', 'status', 'created_at as create_time'
            ])
            ->get();

        return $this->jsonSuccess([
            'list' => $paginate
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