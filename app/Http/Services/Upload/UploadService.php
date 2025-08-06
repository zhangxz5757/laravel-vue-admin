<?php

namespace App\Http\Services\Upload;

use App\Exceptions\ApiException;
use Illuminate\Http\UploadedFile;

/**
 * 文件上传
 */
class UploadService
{

    public function upload(UploadedFile $file)
    {
        $uploadDriver = config('uploads.driver');
        $className = sprintf('App\Http\Services\Upload\Handler\%sHandler', ucfirst($uploadDriver));

        if (!class_exists($className)) {
            throw new ApiException(sprintf("上传配置错误，不支持的上传类型：%s", $uploadDriver), 500);
        }

        /**
         * @var UploadHandler $uploadHandler
         */
        $uploadHandler = app($className, [
            'file' => $file
        ]);

        // 初始化client
        $uploadHandler->init();

        // 执行上传操作
        $isSuccess = $uploadHandler->doUpload();
        $uploadResponse = new UploadResponse();
        $uploadResponse->setStatus($isSuccess);
        $uploadResponse->setHost(config('uploads.url_prefix'));
        $uploadResponse->setRelativePath($uploadHandler->getStorageFilePath() . '/' . $uploadHandler->getStorageFileName());
        $uploadResponse->setUrl($uploadResponse->getHost() . '/' . $uploadResponse->getRelativePath());

        return $uploadResponse;
    }
}
