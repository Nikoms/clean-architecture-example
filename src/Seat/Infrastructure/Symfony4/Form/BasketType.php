<?php declare(strict_types=1);

namespace Symfony4\Form;

use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productId')
            ->add('optionId', TextType::class)
            ->add('comment')
            ->add(
                'supplementIds',
                CollectionType::class,
                [
                    'entry_type' => TextType::class,
                    'empty_data' => [],
                    'by_reference' => false,
                    'allow_add' => true,
                ]
            )
            ->add('quantity', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AddProductToBasketRequest::class,
                'csrf_protection' => false,
            ]
        );
    }
}
