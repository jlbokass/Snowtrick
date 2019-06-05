<?php
/**
 * Created by PhpStorm.
 * User: jean-le-grandbokassa
 * Date: 17/05/2019
 * Time: 07:13
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    private $uploadsPath;
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }
    public function uploadArticleImage(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath;
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