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
        $request = new Request();
        // we just take the test as an file to upload
        $request->files->set('someFile', new UploadedFile(__DIR__ . DIRECTORY_SEPARATOR . 'FileUploaderTest.php', 'fileName'));
        self::assertTrue($this->fileUploader->checkIfUploadIsValid($request, 'fileName'));
    }

    public function setUp(): void
    {
        $entityManagerMock = $this->createMock(EntityManager::class);
        $this->fileUploader = new FileUploader('someString', $entityManagerMock);
    }
}
