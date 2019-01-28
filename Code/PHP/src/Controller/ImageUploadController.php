<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImageUploaderType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
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
     * ImageUploadController constructor.
     * @param EngineInterface $twigEngine
     * @param FormFactory $formFactory
     */
    public function __construct(EngineInterface $twigEngine, FormFactoryInterface $formFactory)
    {
        $this->twigEngine = $twigEngine;
        $this->formFactory = $formFactory;
    }
    /**
     * @Route("/createNewImage", name="uploader_new")
     * @return Response
     */
    public function createNewImage(): Response
    {
        $form = $this->formFactory->create(ImageUploaderType::class)->createView();
        return $this->twigEngine->renderResponse('imageUploader/newUpload.html.twig', ['form' => $form]);
    }
}