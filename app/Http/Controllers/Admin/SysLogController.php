<?php

namespace App\Http\Controllers\Admin;

use App\Models\SysLogModel;
use Illuminate\Http\Request;

class SysLogController extends AdminBaseController
{

    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     */
   public function indexAction(Request $request)
    {
        $paginate = SysLogModel::query()
            ->when($k = $request->get('keyword'), fn ($q) => $q->where('url', 'like', '%' . $k . '%'))
            ->when($k = $request->get('user_id'), fn ($q) => $q->where('user_id', $k))
            ->orderBy('id', 'DESC')
            ->paginate($request->get('per_page'));

        return $this->jsonSuccess($paginate);
    }

    /**
     * 编辑
     *
     * @param Request $request
     * @return void
     */
    public function paramsAction(Request $request)
    {
        $id = $request->get('id');
        $vo = SysLogModel::find($id);
        dump($vo->params);
    }
}
