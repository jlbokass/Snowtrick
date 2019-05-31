<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFilename;

    /**
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    private $uploadsPath;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function getImagePath()
    {
        return 'uploads/article_image/'.$this->getImageFilename();
    }

    /**
     * @ORM\PrePersist()
     */
    public function uploadArticleImage(): string
    {
        if ($this->file === null) {
            return;
        }

        if ($this->id) {
            unlink($this->uploadsPath);
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $this->getFile();

        $destination = $this->uploadsPath;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);

        $newFilename = $this->filenameUrlize($originalFilename) .'-'. uniqid().'.'.$uploadedFile->guessExtension();



        $uploadedFile->move(
            $destination,
            $this->setImageFilename($newFilename)
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
