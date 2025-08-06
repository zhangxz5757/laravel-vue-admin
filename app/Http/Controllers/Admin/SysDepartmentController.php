<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SysDepartmentModel as ThisTable;
use App\Utils\TreeUtils;
use Illuminate\Http\Request;

class SysDepartmentController extends BaseController
{

    private $field = [
        'id', 'title', 'pid', 'sort_id',  'status', 'created_at'
    ];

    private $validate = [
        'title'         => 'required',
        'pid'           => 'required',
        'status'        => 'required',
        'sort_id'       => 'required',
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
        $paginate = ThisTable::query()
            ->when($k = $request->get('keyword'), fn($q) => $q->where('title', 'like', "%{$k}%"))
            ->when($k = $request->get('pid'), fn($q) => $q->where('pid', $k))
            ->orderBy('sort_id')
            ->get();
        foreach ($paginate as $item) {
            $item->create_time = $item->created_at->format('Y-m-d H:i:s');
        }
        $treeData = TreeUtils::makeTree($paginate->toArray(), [
            'children_key' => 'children'
        ]);
        return $this->jsonSuccess([
            'list' => $treeData
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

    public function treeAction(Request $request)
    {
        $list = ThisTable::query()->orderBy('sort_id')->get(['id', 'title', 'pid']);
        $tree = TreeUtils::makeTree($list->toArray(), [
            'children_key' => 'children'
        ]);
        return $this->jsonSuccess($tree);
    }
}
