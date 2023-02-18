<?php

declare(strict_types=1);

namespace App\Template\Application\ParamConverter;

use App\Template\Application\Model\TemplateRenderCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TemplateRenderParamConverter implements ParamConverterInterface
{
    /**
     * convert request content to template render command.
     *
     * @param Request $request,
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $this->checkApiKeyByHeader($request->headers);
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $templateRenderCommand = new TemplateRenderCommand();
        $templateRenderCommand->setSlug($parameters['slug']);
        $templateRenderCommand->setAdditionalVariables($parameters['variables']);
        $request->attributes->set($configuration->getName(), $templateRenderCommand);

        return true;
    }

    private function checkApiKeyByHeader(HeaderBag $headers): void
    {
        if (null == $headers->get('x-api-key')) {
            throw new \Exception('API Key Not Found', Response::HTTP_NOT_FOUND);
        } elseif ($headers->get('x-api-key') != $_ENV['API_KEY']) {
            throw new \Exception('This API Key is not valid', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'templateRenderCommand' === $configuration->getName();
    }
}
