<?php


namespace App\EventListener;


use App\Entity\Article;
use App\Entity\Image;
use App\Service\UploaderHelper;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PreFlush;
use Doctrine\ORM\Mapping\PrePersist;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadHelperListener
{
    private $imageUpload;

    public function __construct(UploaderHelper $imageUpload)
    {
        $this->imageUpload = $imageUpload;
    }

}