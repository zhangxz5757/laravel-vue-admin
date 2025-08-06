<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;
use OSS\Core\OssException;
use OSS\OssClient;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class OssUtils
{

    /**
     * 初始化client
     *
     * @return OssClient
     * @throws OssException
     */
    public static function getClient()
    {
        $appId = ConfigUtils::getValue('web_aliyun_appid');
        $secret = ConfigUtils::getValue('web_aliyun_secret');
        $endpoint = ConfigUtils::getValue('web_aliyun_endpoint');
        return new OssClient($appId, $secret, $endpoint);
    }

    /**
     * 上传
     *
     * @param $localFilePath
     * @param $remoteFilePath
     * @param $deleteLocalFile
     * @return bool
     */
    public static function uploadFile($localFilePath, $remoteFilePath, $deleteLocalFile = false)
    {

        if (!file_exists($localFilePath)) {
            throw new FileNotFoundException($localFilePath);
        }

        $bucket = ConfigUtils::getValue('web_aliyun_bucket');
        try {
            self::getClient()->uploadFile($bucket, $remoteFilePath, $localFilePath);
            $deleteLocalFile && @unlink($localFilePath);
        } catch (OssException $e) {
            Log::warning("上传文件失败", ["msg" => $e->getMessage()]);
            return false;
        }

        return true;
    }
}
