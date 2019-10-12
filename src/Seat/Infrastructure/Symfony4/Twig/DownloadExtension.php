<?php

namespace Symfony4\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

class DownloadExtension extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $twig;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('download', [$this, 'getDownload'], ['is_safe' => ['html']]),
        ];
    }

    public function getDownload($slug)
    {
        try {
            $pdf = $this->entityManager->getRepository('App:Pdf')->find($slug);
            if ($pdf === null) {
                $this->logger->error(sprintf('No pdf for "%s"', $slug));

                return '';
            }

            return $this->twig->render('component/download.html.twig', ['pdf' => $pdf]);

        } catch (\Exception $ex) {
            $this->logger->error(sprintf('Exception "%s" content for "%s"', $ex->getMessage(), $slug), ['error' => $ex]);

            return '';
        }

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'download_extension';
    }
}
