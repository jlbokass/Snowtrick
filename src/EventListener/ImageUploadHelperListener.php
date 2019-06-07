<?php

namespace App\EventListener;

use App\Service\UploaderHelper;

class ImageUploadHelperListener
{
    private $imageUpload;

    public function __construct(UploaderHelper $imageUpload)
    {
        $this->imageUpload = $imageUpload;
    }
}