<?php

declare(strict_types=1);

namespace App\Verification\Application\ParamConverter;

use App\Verification\Application\Model\CreateVerificationCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateVerificationParamConverter implements ParamConverterInterface
{
    /**
     * convert request content to create verification command.
     *
     * @param Request $request,
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (!array_key_exists('subject', $parameters)) {
            throw new \InvalidArgumentException('Subject Parameter not found');
        }
        $createVerificationCommand = new CreateVerificationCommand($parameters['subject']['identity'],$parameters['subject']['type']);
        $request->attributes->set($configuration->getName(), $createVerificationCommand);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'createVerificationCommand' === $configuration->getName();
    }
}
