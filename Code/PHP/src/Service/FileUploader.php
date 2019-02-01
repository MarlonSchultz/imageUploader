<?php

namespace App\Service;

use App\Entity\UploadedImages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileUploader
{
    private $targetDirectory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct($targetDirectory, EntityManagerInterface $entityManager)
    {
        $this->targetDirectory = $targetDirectory;
        $this->entityManager = $entityManager;
    }

    public function checkIfUploadIsValid(Request $request, string $uploadName): bool
    {
        /** @var UploadedFile $file */
        $file = $request->files->get($uploadName);
        if (null === $file) {
            return false;
        }
        
        return $file->isValid();
    }

    public function uploadAndPersistToDb(UploadedFile $file): string
    {
        if (!$file->isValid()) {
            throw new UploadException('File not valid');
        }

        $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
            $newImage = new UploadedImages();
            $newImage->setFileName($fileName);
            $this->entityManager->persist($newImage);
            $this->entityManager->flush();
        } catch (FileException $e) {
            throw new UploadException('Moving file failed:' . $e->getMessage());
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}