<?php declare(strict_types=1);

namespace Symfony4\DependencyInjection\Compiler;

use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutowireSeatPass implements CompilerPassInterface
{
    public function __construct()
    {
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $ids = $container->findTaggedServiceIds('seat.autowire');
        foreach (array_keys($ids) as $className) {
            $rf = new ReflectionClass($className);
            foreach ($rf->getInterfaces() as $interface) {
                if (strpos($interface->getName(), 'Seat') === 0) {
                    $container->autowire($interface->getName(), $className);
                }
            }
        }
    }

}
