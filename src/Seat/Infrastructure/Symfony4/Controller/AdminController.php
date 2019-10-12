<?php
declare(strict_types=1);

namespace Symfony4\Controller;

use Infrastructure\Persistence\Command;
use Doctrine\ORM\Query\Expr\Join;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends EasyAdminController
{
    protected function listCommandLaHulpeAction()
    {
        $this->listen();

        return parent::listAction();
    }

    protected function listCommandWaterlooAction()
    {
        $this->listen();

        return parent::listAction();
    }

    private function listen()
    {
        $this->get('event_dispatcher')->addListener(
            EasyAdminEvents::POST_LIST,
            function (GenericEvent $event) {
                $paginator = $event->getSubject();
                $userIds = [];
                /** @var Command $command */
                foreach ($paginator as $command) {
                    $userIds[] = $command->userId();
                }
            }
        );

        return parent::listAction();
    }

    public function renderCommandWaterlooTemplate($type, $twigPath, $parameters)
    {
        return $this->renderCommandOfTodayTemplate($type, $twigPath, $parameters, 'waterloo');
    }

    public function renderCommandLaHulpeTemplate($type, $twigPath, $parameters)
    {
        return $this->renderCommandOfTodayTemplate($type, $twigPath, $parameters, 'la-hulpe');
    }

    protected function renderCommandOfTodayTemplate($type, $twigPath, $parameters, $store): Response
    {
        if ($type !== 'list') {
            return $this->renderTemplate($type, $twigPath, $parameters);
        }

        $commands = [];
        /** @var Command $commandOfUser */
        foreach ($parameters['paginator'] as $commandOfUser) {
            if (!isset($commands[$commandOfUser->userId()])) {
                $commands[$commandOfUser->userId()] = [];
            }
            $commands[$commandOfUser->userId()][] = $commandOfUser;
        }
        $total = 0;

        $companies = $this->em->createQueryBuilder()
            ->select(['c.id', 'c.name', 't.name as tourneeName', 'c.canBeDelivered', 'u.id as user_id'])
            ->from('App:Company', 'c')
            ->join('App:User', 'u', Join::WITH, 'c.id = u.company')
            ->join('App:Tournee', 't', Join::WITH, 'c.tournee = t.id')
            ->where('u.id in (:userIds)')
            ->andWhere('c.store = :store')
            ->orderBy('c.name', 'ASC')
            ->setParameter('userIds', array_keys($commands))
            ->setParameter('store', $store)
            ->getQuery()
            ->getResult();

        $companiesWithUsers = [];
        foreach ($companies as $company) {
            if (!isset($companiesWithUsers[$company['id']])) {
                $orderType = $company['canBeDelivered'] ? 'Livraison "'.$company['tourneeName'].'"' : 'À emporter';
                $companiesWithUsers[$company['id']] = [
                    'name' => $company['name'].' ('.$orderType.')',
                    'users' => [],
                ];
            }
            $companiesWithUsers[$company['id']]['users'][] = $company['user_id'];
        }

        $commandsWithCompany = [];

        foreach ($companiesWithUsers as $company) {
            $commandsWithCompany[] = $company['name'];
            foreach ($company['users'] as $userId) {
                foreach ($commands[$userId] as $commandOfUser) {
                    $commandsWithCompany[] = $commandOfUser;
                    $total++;
                }
                unset($commands[$userId]);
            }
        }
        if (!empty($commands)) {
            $userStore = $this->em->createQueryBuilder()
                ->select(['u.id', 'u.store'])
                ->from('App:User', 'u', 'u.id')
                ->where('u.id in (:userIds)')
                ->setParameter('userIds', array_keys($commands))
                ->getQuery()
                ->getResult();
            $userCommands = [];
            foreach ($commands as $commandOfUser) {
                foreach ($commandOfUser as $command) {
                    if ($userStore[$command->userId()]['store'] === $store) {
                        $userCommands[] = $command;
                        $total++;
                    }
                }
            }
            if (!empty($userCommands)) {
                $commandsWithCompany[] = 'Client sans société';
                array_push($commandsWithCompany, ...$userCommands);
            }
        }

        return $this->renderTemplate($type, $twigPath, array_merge($parameters, ['commandsWithCompany' => $commandsWithCompany, 'total' => $total]));
    }
}
