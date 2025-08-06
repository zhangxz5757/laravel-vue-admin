<?php

namespace App\Http\Services;

use App\Models\ProjectMessageModel;

class ProjectMessageService
{

    /**
     * 创建项目/编辑项目 10/11
     * 上传文档/删除文档 20/21
     * 添加成员/删除成员 30/31
     * 添加资产/删除资产 40/41
     * 添加审批流/编辑审批流/删除审批流 50/51/52
     * 上传审批流文件      60
     *
     * @var array
     */
    protected $action = [];

    public static function addService($projectId, $userId, $action, $message)
    {

        ProjectMessageModel::query()->create([
            'project_id' => $projectId,
            'created_by' => $userId,
            'action' => $action,
            'message' => $message
        ]);
        return true;
    }
}