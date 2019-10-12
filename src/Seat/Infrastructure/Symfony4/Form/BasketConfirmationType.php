<?php declare(strict_types=1);

namespace Symfony4\Form;

use Seat\Domain\Basket\Model\OrderTypeName;
use Seat\Domain\Basket\Model\PossibleOrderType;
use Seat\Domain\Basket\Service\OrderTypeChecker;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BasketConfirmationType extends AbstractType
{
    private $orderTypeChecker;
    /**
     *
     */
    private $userId;

    public function __construct(OrderTypeChecker $orderTypeChecker, TokenStorageInterface $tokenStorage)
    {
        $this->orderTypeChecker = $orderTypeChecker;
        $this->userId = is_string($tokenStorage->getToken()->getUser()) ? '' : $tokenStorage->getToken()->getUser()->getId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('checkSum', HiddenType::class);
        try {
            $possibleOrderType = $this->orderTypeChecker->getPossibleOrderType($this->userId);
            $builder->add('orderTypeName', HiddenType::class, ['data' => $possibleOrderType->name()]);
            $this->buildForOrderType($builder, $possibleOrderType);
        } catch (\Exception $e) {
            //Can explode when the user is unknown
        }
    }

    private function buildForOrderType(FormBuilderInterface $builder, PossibleOrderType $possibleOrderType): void
    {
        if ($possibleOrderType->name() == OrderTypeName::$takeAway) {
            $roundedSteps = array_reduce(
                $possibleOrderType->range()->getRoundedStep(15),
                function ($list, $time) {
                    $list[substr($time, 0, 5)] = $time;

                    return $list;
                },
                []
            );
            $builder->add('takeAwayTime', ChoiceType::class, ['choices' => $roundedSteps]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ConfirmBasketRequest::class,
                'userId' => '',
                'csrf_protection' => false,
                'possibleOrderType' => null,
            ]
        );
    }
}
