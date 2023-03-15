<?php

declare(strict_types=1);

namespace App\Template\Application\Controller\Api;

use App\Template\Application\Model\TemplateRenderCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/templates/render", name="api_templates_render", methods={"POST"})
 *
 *
 */
final class RenderController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $templateRenderCommand = new TemplateRenderCommand($parameters['slug'],$parameters['variables']);
        return $this->handle($templateRenderCommand);
    }
}
