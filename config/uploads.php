<?php

return [
    // 图片存储介质
    'driver' => env('UPLOAD.DRIVER', 'local'), // 可以是 local 或者 oss。目前只支持本地，和阿里云oss
    // 上传后图片访问路径前缀
    'url_prefix' => env('UPLOAD.VIEW_URL_PREFIX', config('app.url') . '/storage'),
    // 允许上传的文件类型, 空数组表示允许上传所有类型
    'allow_file_type' => [
        'image/jpeg','image/png','image/gif',
        'video/mp4','video/avi','video/mpeg',
    ],
    // 最大上传图片尺寸
    'max_upload_size' => env('UPLOAD.MAX_SIZE', 10 * 1024 * 1024), // 最大上传文件大小，单位为字节，默认10MB
    // 本地上传配置
    'local' => [
        'base_path' => env('UPLOAD.STORAGE_PATH', storage_path('app/public/uploads')),
    ],
    // oss 上传配置
    'oss' => [
        'appid' => '',
        'appsecret' => '',
        'bucket' => '',
        'endpoints' => ''
    ],
];
