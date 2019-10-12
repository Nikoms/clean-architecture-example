<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Symfony4\Doctrine\HtmlContent as HtmlContentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Seat\Domain\Cms\Entity\HtmlContent;
use Seat\Domain\Cms\Entity\HtmlContentRepository;

class DoctrineHtmlContentRepository extends ServiceEntityRepository implements HtmlContentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HtmlContentEntity::class);
    }

    public function getNamed(string $name): HtmlContent
    {
        try {
            /** @var HtmlContentEntity $htmlContent */
            $htmlContent = $this->createQueryBuilder('p')
                ->where('p.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getSingleResult();

            return new HtmlContent($htmlContent->getName(), $htmlContent->getHtml());
        } catch (Exception $e) {
            return new HtmlContent($name, '');
        }
    }
}
