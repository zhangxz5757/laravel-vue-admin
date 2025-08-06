<?php

namespace App\Http\Services\Upload;

class UploadResponse
{
    // 上传状态
    private $status = false;

    // 全路径
    private $url;

    // 资源域名
    private $host;

    // 相对路径
    private $relativePath;

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * @param mixed $relativePath
     */
    public function setRelativePath($relativePath): void
    {
        $this->relativePath = $relativePath;
    }
}
