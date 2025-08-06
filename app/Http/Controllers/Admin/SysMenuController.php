<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SysMenuModel as ThisTable;
use App\Utils\TreeUtils;
use Illuminate\Http\Request;

class SysMenuController extends BaseController
{

    private $validate = [
        'name' => 'required',
        'component' => 'required_if:type,0',
        'path' => 'nullable',
        'title' => 'required',
        'hidden' => 'required|in:0,1',
        'affix' => 'required|in:0,1',
        'pid' => 'required',
        'icon' => 'required_if:type,0',
        'sort_id' => 'required_if:type,0',
        'type' => 'required|in:0,1,2'
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
            ->when($k = $request->get('pid'), fn ($q) => $q->where('pid', $k))
            ->orderBy('sort_id')
            ->paginate($request->get('per_page'));
        return $this->jsonSuccess($paginate);
    }


    /*
     * 列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function treeAction(Request $request)
    {
        $paginate = ThisTable::query()
            ->orderBy('sort_id')
            ->get()->toArray();

        $tree = TreeUtils::makeTree($paginate);
        return $this->jsonSuccess($tree);
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
