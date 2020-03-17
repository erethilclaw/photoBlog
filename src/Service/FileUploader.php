<?php


namespace App\Service;


use App\Entity\Image;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function uploadImage ($image){
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();

        try {
            $image->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function removeAll(){
        $files = glob($this->targetDirectory.'*'); //get all file names
        //dd($files);
        foreach($files as $file){
            if(is_file($file))
                unlink($file); //delete file
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

}