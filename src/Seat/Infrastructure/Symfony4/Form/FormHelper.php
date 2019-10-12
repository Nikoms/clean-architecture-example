<?php declare(strict_types=1);

namespace Symfony4\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class FormHelper
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getFormData(Request $request, string $typeClass, $options = [])
    {
        $formBuilder = $this->formFactory->createBuilder($typeClass, null, $options);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        return $form->getData();
    }
}
