<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Pdf
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $pdf;

    /**
     * @Vich\UploadableField(mapping="pdf", fileNameProperty="pdf")
     * @var File
     */
    private $pdfFile;

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function text()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function pdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
    }

    public function pdfFile(): ?File
    {
        return $this->pdfFile;
    }

    public function setPdfFile($pdfFile)
    {
        $this->pdfFile = $pdfFile;
        if($this->pdfFile){
            $this->setPdf($this->pdfFile->getFilename());
        }
    }


}
