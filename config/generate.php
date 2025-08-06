<?php

return [
    // model fillable 过滤字段
    'modelFillableIgnore' => ['id', 'created_at', 'updated_at', 'deleted_at'],


    // 列表页不显示的字段
    'indexViewBlack' => ['id', 'content', 'created_at', 'updated_at', 'deleted_at'],
    // 列表页switch字段
    'indexViewSwitch' => ['status'],
    // 列表页图片字段
    'indexViewImg' => ['picurl', 'avatar', 'cover_url'],


    'updateViewBlack' => ['id', 'created_at', 'updated_at', 'deleted_at'],
    'selectBlock' => ['type_id'],
    'editorBlock' => ['content'],
    'radioBlock' => ['status'],
    'uploadImgBlock' => ['picurl', 'avatar', 'cover_url'],
    'textBlock' => ['intro']
];
