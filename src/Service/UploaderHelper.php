<?php
/**
 * Created by PhpStorm.
 * User: jean-le-grandbokassa
 * Date: 17/05/2019
 * Time: 07:13
 */

namespace App\Service;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    private $uploadsPath;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadArticleImage(Image $file): string
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $file->getFile();

        $destination = $this->uploadsPath.'/article_image';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);

        $newFilename = $this->filenameUrlize($originalFilename) .'-'. uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }

    public function filenameUrlize(string $filename): string
    {
        $filename = strtolower($filename);
        $filename = strtr($filename, "àäåâôöîïûüéè", "aaaaooiiuuee");
        $filename = str_replace(' ', '-', $filename);

        return $filename;
    }
}