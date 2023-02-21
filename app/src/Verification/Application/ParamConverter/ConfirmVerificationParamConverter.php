<?php

declare(strict_types=1);

namespace App\Verification\Application\ParamConverter;

use App\Verification\Application\Model\ConfirmVerificationCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class ConfirmVerificationParamConverter implements ParamConverterInterface
{
    /**
     * convert request content to confirm verification command.
     *
     * @param Request $request,
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $parameters = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (null == $request->attributes->get('id')) {
            throw new \InvalidArgumentException('The verification Id Not exist on the URL');
        }
        $id = $request->attributes->get('id');
        $confirmVerificationCommand = new ConfirmVerificationCommand($id,$parameters['code']);
        $request->attributes->set($configuration->getName(), $confirmVerificationCommand);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return 'confirmVerificationCommand' === $configuration->getName();
    }
}
