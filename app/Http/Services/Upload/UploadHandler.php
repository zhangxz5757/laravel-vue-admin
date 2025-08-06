<?php

namespace App\Http\Services\Upload;

use App\Exceptions\ApiException;
use Illuminate\Http\UploadedFile;

/**
 * 文件上传适配器
 */
abstract class UploadHandler
{

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * 上传后的文件名称
     *
     * @var string
     */
    protected $storageFileName;

    /**
     * 上传后的文件路径
     *
     * @var string
     */
    protected $storageFilePath;

    /**
     * 允许上传的文件类型 MimeType
     *
     * @var array
     */
    protected $allowFileType = [];

    public function __construct(UploadedFile $file = null)
    {
        $this->file = $file;
        $this->allowFileType = config('upload.allow_file_type', []);

        $this->checkFile();
    }

    abstract function init();

    /**
     * 检查文件
     *
     * @return void
     * @throws ApiException
     */
    public function checkFile()
    {
        if (!$this->file instanceof UploadedFile) {
            throw new ApiException('上传的文件不能为空');
        }

        if (!$this->file->isValid()) {
            throw new ApiException('上传的文件无效: ' . $this->file->getErrorMessage());
        }

        if ($this->file->getSize() > config('uploads.max_upload_size')) {
            throw new ApiException('上传的文件大小超过限制: ' . (int) ($this->file->getSize() / 1024 / 1024) . ' mb');
        }

        if (!empty($this->allowFileType) && !in_array($this->file->getMimeType(), $this->allowFileType)) {
            throw new ApiException('不允许上传的文件类型: ' . $this->file->getMimeType());
        }
    }

    /**
     * 获取文件上传后保存的路径
     *
     * @return string
     */
    public function getStorageFilePath(): string
    {
        return $this->storageFilePath;
    }

    /**
     * 设置文件上传后保存的路径
     *
     * @return void
     */
    public function setStorageFilePath(string $storageFilePath): void
    {
        $this->storageFilePath = $storageFilePath;
    }

    /**
     * 设置上传后的文件名称
     *
     * @param string $fileName
     * @return void
     */
    public function setStorageFileName($fileName = '')
    {
        if (empty($fileName)) {
            $fileName = uniqid() . '.' . $this->file->getClientOriginalExtension();
        }
        $this->storageFileName = $fileName;
    }

    /**
     * 获取上传后的文件名称
     *
     * @return string
     */
    public function getStorageFileName()
    {
        return $this->storageFileName;
    }

    /**
     * 执行上传
     *
     * @param string $filePath 文件上传后的路径
     * @return bool
     */
    abstract function doUpload();

    /**
     * 删除文件
     *
     * @return mixed
     */
    abstract function delLocalFile();
}
