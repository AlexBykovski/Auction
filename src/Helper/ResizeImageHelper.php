<?php

namespace App\Helper;

use App\Upload\FileUpload;
use Imagick;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResizeImageHelper
{
    /**
     * @var FileUpload
     */
    private $uploader;

    /** @var string */
    private $uploadDirectory;

    /**
     * LoginHelper constructor.
     * @param FileUpload $uploader
     * @param string $uploadDirectory
     */
    public function __construct(FileUpload $uploader, $uploadDirectory)
    {
        $this->uploader = $uploader;
        $this->uploader->setFolder(FileUpload::TMP);

        $this->uploadDirectory = $uploadDirectory . '/';
    }

    public function getBlobUserProfileResizeImage(UploadedFile $image)
    {
        $fullPath = $this->uploadTmpFile($image);

        return $this->resizeImageForUserProfile($fullPath);
    }

    public function uploadBlobFile(UploadedFile $file, $blob, $folderUpload)
    {
        $this->uploader->setFolder($folderUpload);

        return $this->uploader->upload($file, $blob);
    }

    protected function uploadTmpFile(UploadedFile $file)
    {
        return $this->uploadDirectory . $this->uploader->upload($file);
    }

    protected function resizeImageForUserProfile($fileFullPath)
    {
        list($width, $height) = getimagesize($fileFullPath);

        $newX = 0;
        $newY = 0;

        if($width > $height){
            $newSize = $height;
            $newX = ($width - $height) / 2;

        }
        else{
            $newSize = $width;
            $newY = ($height - $width) / 2;
        }

        $imagick = new Imagick(realpath($fileFullPath));
        $imagick->cropImage($newSize, $newSize, $newX, $newY);

        return $imagick->getImageBlob();
    }
}