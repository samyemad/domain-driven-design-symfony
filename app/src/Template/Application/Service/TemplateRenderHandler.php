<?php

declare(strict_types=1);

namespace App\Template\Application\Service;

use App\Template\Application\Model\TemplateRenderCommand;
use App\Template\Domain\Entity\Content;
use App\Template\Domain\Entity\Template;
use App\Template\Domain\Entity\TemplateId;
use App\Template\Domain\Repository\TemplateRepositoryInterface;
use App\Template\Domain\Service\Interfaces\TemplateResponseRenderInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TemplateRenderHandler implements MessageHandlerInterface
{
    private TemplateRepositoryInterface $templateRepository;
    private TemplateResponseRenderInterface $templateResponseRender;

    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        TemplateResponseRenderInterface $templateResponseRender
    ) {
        $this->templateRepository = $templateRepository;
        $this->templateResponseRender = $templateResponseRender;
    }

    /**
     * handle template command and handle template twigs based on slug and variables.
     */
    public function __invoke(TemplateRenderCommand $templateRenderCommand): Response
    {
        $response = $this->templateResponseRender->process($templateRenderCommand->getAdditionalVariables(), $templateRenderCommand->getSlug());
        $templateId = new TemplateId(Uuid::uuid4()->toString());
        $template = new Template($templateId);
        $content = new Content($response->getContent());
        $template->create($templateRenderCommand->getSlug(), $content);
        $this->templateRepository->save($template);

        return $response;
    }
}
