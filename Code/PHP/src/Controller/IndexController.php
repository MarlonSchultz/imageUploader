<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    /**
     * @var TwigEngine
     */
    private $twigEngine;

    public function __construct(EngineInterface $twigEngine)
    {
        $this->twigEngine = $twigEngine;
    }

    public function index(): Response
    {
        return $this->twigEngine->renderResponse('base.html.twig');
    }
}