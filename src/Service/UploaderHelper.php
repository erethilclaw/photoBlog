<?php


namespace App\Service;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const ARTICLE_IMAGE = 'article_image';

    private $requestStackContext;
    private $filesystem;
    private $logger;
    private $publicAssetBaseUrl;
    private $privateFilesystem;
    const ARTICLE_REFERENCE = 'article_reference';

    public function __construct(RequestStackContext $requestStackContext, FilesystemInterface $publicUploadsFilesystem, LoggerInterface $logger, string $uploadedAssetsBaseUrl, FilesystemInterface $privateUploadsFilesystem)
    {
        $this->requestStackContext = $requestStackContext;
        $this->filesystem = $publicUploadsFilesystem;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
        $this->privateFilesystem = $privateUploadsFilesystem;
    }

    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext->getBasePath().$this->publicAssetBaseUrl.'/'.$path;
    }

    public function uploadArticleImage(UploadedFile $file, ?string $existingFilename ): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $this->uploadFile($file, self::ARTICLE_IMAGE, true);

       
    }

    public function uploadArticleReference(UploadedFile $file): string
    {
        return $this->uploadFile($file, self::ARTICLE_REFERENCE, false);
    }

    private function uploadFile(UploadedFile $file, string $directory, bool $isPublic): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFilename = pathinfo($originalFilename, PATHINFO_FILENAME).'-'.uniqid().'.'.$file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');

        //$filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;
        
        $result = $this->filesystem->writeStream(
            self::ARTICLE_IMAGE.'/'.$newFilename,
            $stream
        );
        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        return $newFilename;
    }

    /**
     * @return resource
     */
    public function readStream(string $path, bool $isPublic)
    {
        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;

        $resource = $filesystem->readStream($path);

        if ($resource === false) {
            throw new \Exception(sprintf('Error opening stream for "%s"', $path));
        }
        return $resource;
    }
}
