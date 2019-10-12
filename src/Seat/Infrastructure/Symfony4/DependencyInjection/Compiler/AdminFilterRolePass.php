<?php declare(strict_types=1);

namespace Symfony4\DependencyInjection\Compiler;

use EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigPassInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminFilterRolePass implements ConfigPassInterface
{
    private $authorizationChecker;
    private $tokenStorage;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function process(array $backendConfig)
    {
        $entities = [];
        $user = $this->tokenStorage->getToken()->getUser();

        foreach ($backendConfig['entities'] as $class => $entity) {
            if (isset($entity['role']) && $this->authorizationChecker->isGranted($entity['role'], $user)) {
                $entities[$class] = $entity;
            }

        }
        $backendConfig['entities'] = $entities;

        return $backendConfig;
    }

}
