<?php

namespace App\Http\Services;

use App\Utils\ConfigUtils;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use OSS\Core\OssException;
use OSS\OssClient;

class OssService
{

    protected $config = [];
    public function __construct()
    {

        $this->config = Config::get('aliyun');
    }

    public function uploadFile($localFile, $remoteFileName)
    {
        $client = new OssClient(ConfigUtils::getValue('web_aliyun_appid'), ConfigUtils::getValue('web_aliyun_secret'), ConfigUtils::getValue('web_aliyun_endpoint'));

        if (!file_exists($localFile)) {
            return false;
        }

        try {
            $client->uploadFile($this->config['bucket'], $remoteFileName, $localFile);
            @unlink($localFile);
        } catch (OssException $e) {
            Log::warning("上传文件失败", ["msg" => $e->getMessage()]);
            return false;
        }

        return ConfigUtils::getValue('web_oss_domain') . '/'. $remoteFileName;
    }
}
