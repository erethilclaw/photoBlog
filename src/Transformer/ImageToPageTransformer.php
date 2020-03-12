<?php


namespace App\Transformer;


use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ImageToPageTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($image)
    {
        if ($image == null) {
            return '';
        }

        return $image->getId();
    }

    public function reverseTransform($codeImage)
    {
        if ($codeImage == null) {
            return '';
        }

        $image = $this->entityManager->getRepository(Image::class)->find($codeImage);

        if (null === $image) {

            throw new TransformationFailedException(sprintf(
                'la imatge "%s" no existeix', $codeImage
            ));
        }

        return $image;

    }
}