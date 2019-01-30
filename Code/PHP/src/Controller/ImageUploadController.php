<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImageUploaderType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
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
     * @Route("/createNewImage", name="uploader_new")
     * @param Request $request
     * @return Response
     */
    public function createNewImage(Request $request): Response
    {
        $form = $this->formFactory->create(ImageUploaderType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            $this->fileUploader->uploadAndPersistToDb($file);
        }
        return $this->twigEngine->renderResponse('imageUploader/newUpload.html.twig', ['form' => $form->createView()]);
    }
}