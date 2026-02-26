<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Devise
 *
 * @ORM\Table(name="dbx45ty_devise")
 * @ORM\Entity
 */
class Devise extends Entity
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
     * @ORM\Column(name="code_iso", type="string", length=10, nullable=false)
     */
    protected $codeIso;

    /**
     * @var string
     *
     * @ORM\Column(name="code_iso_numerique", type="string", length=10, nullable=false)
     */
    protected $codeIsoNumerique;

    /**
     * @var string
     *
     * @ORM\Column(name="symbole", type="string", length=10, nullable=false)
     */
    protected $symbole;

    /**
     * @var string
     *
     * @ORM\Column(name="separateur_milliers", type="string", length=5, nullable=false)
     */
    protected $separateurMilliers;

    /**
     * @var string
     *
     * @ORM\Column(name="separateur_decimaux", type="string", length=5, nullable=false)
     */
    protected $separateurDecimaux;

    /**
     * @var string
     *
     * @ORM\Column(name="taux_conversion", type="decimal", precision=13, scale=6, nullable=false)
     */
    protected $tauxConversion;

    /**
     * @var string
     *
     * @ORM\Column(name="position_symbole", type="string", nullable=false)
     */
    protected $positionSymbole;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut;

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
     * Set codeIso
     *
     * @param string $codeIso
     *
     * @return Devise
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
     * Set codeIsoNumerique
     *
     * @param string $codeIsoNumerique
     *
     * @return Devise
     */
    public function setCodeIsoNumerique($codeIsoNumerique)
    {
        $this->codeIsoNumerique = $codeIsoNumerique;

        return $this;
    }

    /**
     * Get codeIsoNumerique
     *
     * @return string
     */
    public function getCodeIsoNumerique()
    {
        return $this->codeIsoNumerique;
    }

    /**
     * Set symbole
     *
     * @param string $symbole
     *
     * @return Devise
     */
    public function setSymbole($symbole)
    {
        $this->symbole = $symbole;

        return $this;
    }

    /**
     * Get symbole
     *
     * @return string
     */
    public function getSymbole()
    {
        return $this->symbole;
    }

    /**
     * Set separateurMilliers
     *
     * @param string $separateurMilliers
     *
     * @return Devise
     */
    public function setSeparateurMilliers($separateurMilliers)
    {
        $this->separateurMilliers = $separateurMilliers;

        return $this;
    }

    /**
     * Get separateurMilliers
     *
     * @return string
     */
    public function getSeparateurMilliers()
    {
        return $this->separateurMilliers;
    }

    /**
     * Set separateurDecimaux
     *
     * @param string $separateurDecimaux
     *
     * @return Devise
     */
    public function setSeparateurDecimaux($separateurDecimaux)
    {
        $this->separateurDecimaux = $separateurDecimaux;

        return $this;
    }

    /**
     * Get separateurDecimaux
     *
     * @return string
     */
    public function getSeparateurDecimaux()
    {
        return $this->separateurDecimaux;
    }

    /**
     * Set tauxConversion
     *
     * @param string $tauxConversion
     *
     * @return Devise
     */
    public function setTauxConversion($tauxConversion)
    {
        $this->tauxConversion = $tauxConversion;

        return $this;
    }

    /**
     * Get tauxConversion
     *
     * @return string
     */
    public function getTauxConversion()
    {
        return $this->tauxConversion;
    }

    /**
     * Set positionSymbole
     *
     * @param string $positionSymbole
     *
     * @return Devise
     */
    public function setPositionSymbole($positionSymbole)
    {
        $this->positionSymbole = $positionSymbole;

        return $this;
    }

    /**
     * Get positionSymbole
     *
     * @return string
     */
    public function getPositionSymbole()
    {
        return $this->positionSymbole;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Devise
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
     * @return Devise
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
