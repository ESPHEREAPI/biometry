<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * LignePrestationAudit
 *
 * @ORM\Table(name="dbx45ty_ligne_prestation_audit", indexes={@ORM\Index(name="ligne_prestation_id", columns={"ligne_prestation_id"}), @ORM\Index(name="employe_id", columns={"employe_id"})})
 * @ORM\Entity
 */
class LignePrestationAudit extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_ligne_prestation", type="string", nullable=false)
     */
    protected $etatLignePrestation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @var \Entity\LignePrestation
     *
     * @ORM\ManyToOne(targetEntity="Entity\LignePrestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ligne_prestation_id", referencedColumnName="id")
     * })
     */
    protected $lignePrestation;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set etatLignePrestation
     *
     * @param string $etatLignePrestation
     *
     * @return LignePrestationAudit
     */
    public function setEtatLignePrestation($etatLignePrestation)
    {
        $this->etatLignePrestation = $etatLignePrestation;

        return $this;
    }

    /**
     * Get etatLignePrestation
     *
     * @return string
     */
    public function getEtatLignePrestation()
    {
        return $this->etatLignePrestation;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return LignePrestationAudit
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set lignePrestation
     *
     * @param \Entity\LignePrestation $lignePrestation
     *
     * @return LignePrestationAudit
     */
    public function setLignePrestation(\Entity\LignePrestation $lignePrestation = null)
    {
        $this->lignePrestation = $lignePrestation;

        return $this;
    }

    /**
     * Get lignePrestation
     *
     * @return \Entity\LignePrestation
     */
    public function getLignePrestation()
    {
        return $this->lignePrestation;
    }

    /**
     * Set employe
     *
     * @param \Entity\Employe $employe
     *
     * @return LignePrestationAudit
     */
    public function setEmploye(\Entity\Employe $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \Entity\Employe
     */
    public function getEmploye()
    {
        return $this->employe;
    }
}
