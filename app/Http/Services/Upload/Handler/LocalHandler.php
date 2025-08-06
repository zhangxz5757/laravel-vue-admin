<?php

namespace App\Http\Services\Upload\Handler;

use App\Exceptions\ApiException;
use App\Http\Services\Upload\UploadHandler;
use Illuminate\Support\Facades\Log;

class LocalHandler extends UploadHandler
{

    private $fullPath = '';

    public function init()
    {
        $this->setStorageFileName();

        $basePath = config('uploads.local.base_path');
        $relativePath = date('Ymd');
        $this->setStorageFilePath($relativePath);

        $this->fullPath = $basePath . DIRECTORY_SEPARATOR . $relativePath;


        if (!file_exists($this->fullPath)) {
            try {
                mkdir($this->fullPath, 0777, true);
            } catch (\ErrorException $e) {
                throw new ApiException(sprintf("创建目录失败: %", $e->getMessage()), 500);
            }
        }
    }

    function doUpload()
    {
        $fileUrl = $this->fullPath . '/' . $this->getStorageFileName();
        dump($this->file);die;
        try {
            $res = $this->file->move($fileUrl);
            dump($res);
        } catch (\ErrorException $e) {
            Log::error("上传文件失败", [
                'driver' => 'local',
                'message' => $e->getMessage()
            ]);
            return false;
        }
        return true;
    }

    function delLocalFile()
    {

    }
}
