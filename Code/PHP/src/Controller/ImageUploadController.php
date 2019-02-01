<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImageUploaderType;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageUploadController
{
    /**
     * @var TwigEngine
     */
    private $twigEngine;
    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var FileUploader
     */
    private $fileUploader;


    /**
     * ImageUploadController constructor.
     * @param EngineInterface $twigEngine
     * @param FormFactoryInterface $formFactory
     * @param FileUploader $fileUploader
     */
    public function __construct(EngineInterface $twigEngine, FormFactoryInterface $formFactory, FileUploader $fileUploader)
    {
        $this->twigEngine = $twigEngine;
        $this->formFactory = $formFactory;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/manualUpload", name="manual_upload")
     * @param Request $request
     * @return Response
     */
    public function manualUpload(Request $request): Response
    {
        $form = $this->formFactory->create(ImageUploaderType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            $this->fileUploader->uploadAndPersistToDb($file);
        }
        return $this->twigEngine->renderResponse('imageUploader/manualUpload.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/dropzoneUpload", name="dropzone_upload")
     * @param Request $request
     * @return Response
     */
    public function dropzoneUpload(Request $request): Response
    {
        return $this->twigEngine->renderResponse('imageUploader/dropzoneUpload.html.twig');
    }

    /**
     * @Route("/xhrUpload", name="xhr_upload", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function xhrUpload(Request $request): JsonResponse
    {
        if (!$this->fileUploader->checkIfUploadIsValid($request, 'uploadedFile')) {
            return new JsonResponse(['uploaded' => false], 200);
        }

        $this->fileUploader->uploadAndPersistToDb($request->files->get('uploadedFile'));
        return new JsonResponse(['uploaded' => true], 200);
    }
}