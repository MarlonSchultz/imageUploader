<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 01.02.19
 * Time: 23:16
 */

namespace App\Test\Unit;

use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileUploaderTest extends TestCase
{

    private $fileUploader;

    public function testIfClassCanBeInstantiated(): void
    {
        self::assertTrue(is_a($this->fileUploader, FileUploader::class));
    }

    public function testIfFileValidityCheckerWorks()
    {
        // last option "test" is to circumvent check if something REALLY has been uploaded
        $file = new UploadedFile(
            __DIR__.'/Fixtures/10-kernel-view.png',
            '10-kernel-view.png',
            'image/png',
            null,
            true
        );

        self::assertTrue($this->fileUploader->checkIfUploadIsValid($file));
    }

    public function setUp(): void
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $this->fileUploader = new FileUploader('someString', $entityManagerMock);
    }
}
