<?php

namespace Symfony4\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;

class ContentExtension extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('content', [$this, 'getContent'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $slug
     *
     * @return string
     */
    public function getContent($slug)
    {
        try {
            $htmlContent = $this->entityManager->getRepository('App:HtmlContent')->findOneBy(
                [
                    'name' => $slug,
                ]
            );
            if ($htmlContent === null) {
                $this->logger->error(sprintf('No content for "%s"', $slug));

                return '';
            }

            return $htmlContent->getHtml();
        } catch (\Exception $ex) {
            $this->logger->error(sprintf('Exception "%s" content for "%s"', $ex->getMessage(), $slug));

            return '';
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_extension';
    }
}
