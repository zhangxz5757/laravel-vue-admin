<?php

return [
    'ak' => env('HUAWEICLOUD_AK'),
    'sk' => env('HUAWEICLOUD_SK'),
    'sign_channel' => env('HUAWEICLOUD_SIGN_CHANNEL'),
    'code_template' => env('HUAWEICLOUD_CODE_TEMPLATE'),

    'obs' => [
        'ak' => env('HUAWEI_OBS_AK'),
        'sk' => env('HUAWEI_OBS_SK'),
        'endpoints' => env('HUAWEU_OBS_ENDPOINTS'),
        'domain' => env('HUAWEI_OBS_DOMAIN'),
        'bucket' => env('HUAWEI_OBS_BUCKET'),
    ]
];