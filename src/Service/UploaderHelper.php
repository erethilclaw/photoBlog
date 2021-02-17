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

    public function __construct(RequestStackContext $requestStackContext, FilesystemInterface $publicUploadsFilesystem, LoggerInterface $logger)
    {
        $this->requestStackContext = $requestStackContext;
        $this->filesystem = $publicUploadsFilesystem;
        $this->logger = $logger;
    }

    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext->getBasePath() . '/uploads/' . $path;
    }

    public function uploadArticleImage(UploadedFile $file, ?string $existingFilename ): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFileName . '-' . uniqid() . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');
        $result = $this->filesystem->writeStream(
            self::ARTICLE_IMAGE.'/'.$newFilename,
            $stream
        );
        if (is_resource($stream)) {
            fclose($stream);
        }
        
        if ($existingFilename) {
            try {
               $result = $this->filesystem->delete(self::ARTICLE_IMAGE.'/'.$existingFilename);
            } catch ( FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
            
        }

        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }

        return $newFilename;
    }
}
