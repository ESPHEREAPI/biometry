<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Langue
 *
 * @ORM\Table(name="dbx45ty_langue", uniqueConstraints={@ORM\UniqueConstraint(name="code_UNIQUE", columns={"code"})})
 * @ORM\Entity
 */
class Langue extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="code_iso", type="string", length=10, nullable=false)
     */
    protected $codeIso;

    /**
     * @var string
     *
     * @ORM\Column(name="code_fin", type="string", length=10, nullable=false)
     */
    protected $codeFin;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="format_date", type="string", length=10, nullable=false)
     */
    protected $formatDate;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", nullable=false)
     */
    protected $supprime = '-1';



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Langue
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set codeIso
     *
     * @param string $codeIso
     *
     * @return Langue
     */
    public function setCodeIso($codeIso)
    {
        $this->codeIso = $codeIso;

        return $this;
    }

    /**
     * Get codeIso
     *
     * @return string
     */
    public function getCodeIso()
    {
        return $this->codeIso;
    }

    /**
     * Set codeFin
     *
     * @param string $codeFin
     *
     * @return Langue
     */
    public function setCodeFin($codeFin)
    {
        $this->codeFin = $codeFin;

        return $this;
    }

    /**
     * Get codeFin
     *
     * @return string
     */
    public function getCodeFin()
    {
        return $this->codeFin;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Langue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set formatDate
     *
     * @param string $formatDate
     *
     * @return Langue
     */
    public function setFormatDate($formatDate)
    {
        $this->formatDate = $formatDate;

        return $this;
    }

    /**
     * Get formatDate
     *
     * @return string
     */
    public function getFormatDate()
    {
        return $this->formatDate;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Langue
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set supprime
     *
     * @param string $supprime
     *
     * @return Langue
     */
    public function setSupprime($supprime)
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * Get supprime
     *
     * @return string
     */
    public function getSupprime()
    {
        return $this->supprime;
    }
}
