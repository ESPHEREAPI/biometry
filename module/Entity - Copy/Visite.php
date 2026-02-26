<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visite
 *
 * @ORM\Table(name="dbx45ty_visite", indexes={@ORM\Index(name="code_ayant_droit", columns={"code_ayant_droit"}), @ORM\Index(name="employe_id", columns={"employe_id"}), @ORM\Index(name="code_adherent", columns={"code_adherent"}), @ORM\Index(name="code_prestataire", columns={"prestataire_id"})})
 * @ORM\Entity
 */
class Visite extends \Custom\Model\Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code_court", type="string", length=40, nullable=false)
     */
    protected $codeCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=false)
     */
    protected $telephone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

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
     * @var \Entity\AyantDroit
     *
     * @ORM\ManyToOne(targetEntity="Entity\AyantDroit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_ayant_droit", referencedColumnName="code_ayant_droit")
     * })
     */
    protected $codeAyantDroit;

    /**
     * @var \Entity\Prestataire
     *
     * @ORM\ManyToOne(targetEntity="Entity\Prestataire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prestataire_id", referencedColumnName="id")
     * })
     */
    protected $prestataire;

    /**
     * @var \Entity\Employe
     *
     * @ORM\ManyToOne(targetEntity="Entity\Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe_id", referencedColumnName="id")
     * })
     */
    protected $employe;



    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codeCourt.
     *
     * @param string $codeCourt
     *
     * @return Visite
     */
    public function setCodeCourt($codeCourt)
    {
        $this->codeCourt = $codeCourt;

        return $this;
    }

    /**
     * Get codeCourt.
     *
     * @return string
     */
    public function getCodeCourt()
    {
        return $this->codeCourt;
    }

    /**
     * Set telephone.
     *
     * @param string $telephone
     *
     * @return Visite
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone.
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Visite
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set codeAdherent.
     *
     * @param \Entity\Adherent|null $codeAdherent
     *
     * @return Visite
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

    /**
     * Set codeAyantDroit.
     *
     * @param \Entity\AyantDroit|null $codeAyantDroit
     *
     * @return Visite
     */
    public function setCodeAyantDroit(\Entity\AyantDroit $codeAyantDroit = null)
    {
        $this->codeAyantDroit = $codeAyantDroit;

        return $this;
    }

    /**
     * Get codeAyantDroit.
     *
     * @return \Entity\AyantDroit|null
     */
    public function getCodeAyantDroit()
    {
        return $this->codeAyantDroit;
    }

    /**
     * Set prestataire.
     *
     * @param \Entity\Prestataire|null $prestataire
     *
     * @return Visite
     */
    public function setPrestataire(\Entity\Prestataire $prestataire = null)
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    /**
     * Get prestataire.
     *
     * @return \Entity\Prestataire|null
     */
    public function getPrestataire()
    {
        return $this->prestataire;
    }

    /**
     * Set employe.
     *
     * @param \Entity\Employe|null $employe
     *
     * @return Visite
     */
    public function setEmploye(\Entity\Employe $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe.
     *
     * @return \Entity\Employe|null
     */
    public function getEmploye()
    {
        return $this->employe;
    }
}
