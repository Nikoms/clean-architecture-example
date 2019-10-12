<?php declare(strict_types=1);

namespace Symfony4\Form;

use Seat\Domain\Client\Entity\Store;
use Seat\Domain\Client\UseCase\Register\RegisterRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isPosted', HiddenType::class, ['data' => true])
            ->add('companyName')
            ->add('firstName', null, ['required' => true])
            ->add('lastName', null, ['required' => true])
            ->add('email', null, ['required' => true])
            ->add('phoneNumber', null, ['required' => true])
            ->add('password', PasswordType::class)
            ->add(
                'store',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => [
                        'La hulpe' => Store::$laHulpe,
                        'Waterloo' => Store::$waterloo,
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => RegisterRequest::class,
            ]
        );
    }
}
