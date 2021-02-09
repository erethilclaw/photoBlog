<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    private $uploadPath;

    public function __construct(string $uploadPath )
    {
        $this->uploadPath = $uploadPath;
    }

    public function uploadArticleImage(UploadedFile $uploadedFile): string
    {
        $detination = $this->uploadPath.'/article_image';
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFileName.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($detination, $newFileName);

        return $newFileName;
    }
}