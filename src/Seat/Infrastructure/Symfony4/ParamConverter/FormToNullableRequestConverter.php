<?php declare(strict_types=1);

namespace Symfony4\ParamConverter;

use Symfony4\Form\FormHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class FormToNullableRequestConverter implements ParamConverterInterface
{
    private $formHelper;

    public function __construct(FormHelper $formHelper)
    {
        $this->formHelper = $formHelper;
    }

    /**
     * Stores the object in the request.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $specificRequest = $this->formHelper->getFormData($request, $configuration->getOptions()['form']);

        $request->attributes->set($configuration->getName(), $specificRequest);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {

        return !empty($configuration->getOptions()['form']) && $configuration->getName() === 'nullableRequest';
    }
}
