<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const ARTICLE_IMAGE = 'article_image';

    private $uploadPath;

    public function __construct(string $uploadPath )
    {
        $this->uploadPath = $uploadPath;
    }

    public function getPublicPath(string $path): string
    {
        return 'uploads/'.$path;
    }

    public function uploadArticleImage(UploadedFile $uploadedFile): string
    {
        $detination = $this->uploadPath.'/'.self::ARTICLE_IMAGE;
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFileName.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($detination, $newFileName);

        return $newFileName;
    }
}