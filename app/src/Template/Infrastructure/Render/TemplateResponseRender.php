<?php

declare(strict_types=1);

namespace App\Template\Infrastructure\Render;

use App\Shared\Enums\EnumsType;
use App\Template\Domain\Service\Interfaces\TemplateResponseRenderInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TemplateResponseRender implements TemplateResponseRenderInterface
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * render template based on the slug and inject the view parameters for the template.
     */
    public function process(array $parameters, string $slug): Response
    {
        $response = new Response();
        if (EnumsType::MOBILE_VERIFICATION == $slug) {
            $response = $this->renderTemplateAsText($parameters, $response);
        } elseif (EnumsType::EMAIL_VERIFICATION == $slug) {
            $response = $this->renderTemplateAsHtml($parameters, $response);
        }

        return $response;
    }

    private function renderTemplateAsText(array $parameters, Response $textResponse): Response
    {
        $content = $this->twig->render('@template/mobile_verifications.html.twig', $parameters);
        $textResponse->setContent($content);
        $textResponse->setStatusCode(200);
        $textResponse->headers->set('Content-Type', 'text/plain');

        return $textResponse;
    }

    private function renderTemplateAsHtml(array $parameters, Response $htmlResponse): Response
    {
        $content = $this->twig->render('@template/email_verifications.html.twig', $parameters);
        $htmlResponse->setContent($content);
        $htmlResponse->setStatusCode(200);
        $htmlResponse->setCharset('UTF-8');
        $htmlResponse->headers->set('Content-Type', 'text/html');

        return $htmlResponse;
    }
}
