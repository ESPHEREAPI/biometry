<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;


/**
 * AyantDroit
 *
 * @ORM\Table(name="dbx45ty_ayant_droit", indexes={@ORM\Index(name="code_adherent", columns={"code_adherent"})})
 * @ORM\Entity
 */
class AyantDroit extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="code_ayant_droit", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    protected $codeAyantDroit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexe", type="string", length=5, nullable=true)
     */
    protected $sexe;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="naissance", type="date", nullable=true)
     */
    protected $naissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="police", type="string", length=100, nullable=true)
     */
    protected $police;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=0, nullable=false, options={"default"="1"})
     */
    protected $statut = '1';

    /**
     * @var \Entity\Adherent
     *
     * @ORM\ManyToOne(targetEntity="Entity\Adherent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_adherent", referencedColumnName="code_adherent")
     * })
     */
    protected $codeAdherent;



    /**
     * Get codeAyantDroit.
     *
     * @return string
     */
    public function getCodeAyantDroit()
    {
        return $this->codeAyantDroit;
    }
	
	/**
     * Set codeAyantdroit.
     *
     * @param string $codeAyantDroit
     *
     * @return AyantDroit
     */
    public function setCodeAyantDroit($codeAyantDroit)
    {
        $this->codeAyantDroit = $codeAyantDroit;

        return $this;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return AyantDroit
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set sexe.
     *
     * @param string|null $sexe
     *
     * @return AyantDroit
     */
    public function setSexe($sexe = null)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe.
     *
     * @return string|null
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set naissance.
     *
     * @param \DateTime|null $naissance
     *
     * @return AyantDroit
     */
    public function setNaissance($naissance = null)
    {
        $this->naissance = $naissance;

        return $this;
    }

    /**
     * Get naissance.
     *
     * @return \DateTime|null
     */
    public function getNaissance()
    {
        return $this->naissance;
    }

    /**
     * Set police.
     *
     * @param string|null $police
     *
     * @return AyantDroit
     */
    public function setPolice($police = null)
    {
        $this->police = $police;

        return $this;
    }

    /**
     * Get police.
     *
     * @return string|null
     */
    public function getPolice()
    {
        return $this->police;
    }

    /**
     * Set statut.
     *
     * @param string $statut
     *
     * @return AyantDroit
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut.
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set codeAdherent.
     *
     * @param \Entity\Adherent|null $codeAdherent
     *
     * @return AyantDroit
     */
    public function setCodeAdherent(\Entity\Adherent $codeAdherent = null)
    {
        $this->codeAdherent = $codeAdherent;

        return $this;
    }

    /**
     * Get codeAdherent.
     *
     * @return \Entity\Adherent|null
     */
    public function getCodeAdherent()
    {
        return $this->codeAdherent;
    }
}
