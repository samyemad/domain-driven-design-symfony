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
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $templateRenderCommand = new TemplateRenderCommand();
        $templateRenderCommand->setSlug($parameters['slug']);
        $templateRenderCommand->setAdditionalVariables($parameters['variables']);
        $request->attributes->set($configuration->getName(), $templateRenderCommand);
        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'templateRenderCommand' === $configuration->getName();
    }
}
