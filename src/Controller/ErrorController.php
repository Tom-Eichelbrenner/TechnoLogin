<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function show(FlattenException $exception, Environment $environment): Response
    {
        $view = "bundles/TwigBundle/Exception/error{$exception->getStatusCode()}.html.twig";
        if (!$environment->getLoader()->exists($view)) {
            $view = 'bundles/TwigBundle/Exception/error.html.twig';
        }
        return $this->render($view, [
            'status_code' => $exception->getStatusCode(),
            'status_text' => Response::$statusTexts[$exception->getStatusCode()],
            'exception' => $exception,
        ]);
    }
}