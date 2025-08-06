<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;

class CommonController extends BaseController
{
    /**
     * 文件上传
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function uploadAction(){
        /**
         * @var UploadedFile $file
         */
        $file = request()->file('file');
        $uploadService = new UploadService();
        $uploadResponse = $uploadService->upload($file);

        return $this->jsonSuccess($uploadResponse);
    }
}
