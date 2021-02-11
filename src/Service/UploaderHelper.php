<?php


namespace App\Service;

use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const ARTICLE_IMAGE = 'article_image';

    private $uploadPath;
    private $requestStackContext;

    public function __construct(string $uploadPath, RequestStackContext $requestStackContext)
    {
        $this->uploadPath = $uploadPath;
        $this->requestStackContext = $requestStackContext;
    }

    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext->getBasePath() . '/uploads/' . $path;
    }

    public function uploadArticleImage(UploadedFile $uploadedFile): string
    {
        $detination = $this->uploadPath . '/' . self::ARTICLE_IMAGE;
        $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($detination, $newFileName);

        return $newFileName;
    }
}
