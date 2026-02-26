<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Prestation
 *
 * @ORM\Table(name="dbx45ty_prestation", uniqueConstraints={@ORM\UniqueConstraint(name="visite_id_2", columns={"visite_id", "nature_prestation"})}, indexes={@ORM\Index(name="visite_id", columns={"visite_id"}), @ORM\Index(name="prestataire_id", columns={"prestataire_id"})})
 * @ORM\Entity
 */
class Prestation extends Entity
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
     * @ORM\Column(name="nature_prestation", type="string", nullable=false)
     */
    protected $naturePrestation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", nullable=false)
     */
    protected $supprime = '-1';

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
     * @var \Entity\Visite
     *
     * @ORM\ManyToOne(targetEntity="Entity\Visite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visite_id", referencedColumnName="id")
     * })
     */
    protected $visite;



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
     * Set naturePrestation
     *
     * @param string $naturePrestation
     *
     * @return Prestation
     */
    public function setNaturePrestation($naturePrestation)
    {
        $this->naturePrestation = $naturePrestation;

        return $this;
    }

    /**
     * Get naturePrestation
     *
     * @return string
     */
    public function getNaturePrestation()
    {
        return $this->naturePrestation;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Prestation
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
     * Set supprime
     *
     * @param string $supprime
     *
     * @return Prestation
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

    /**
     * Set prestataire
     *
     * @param \Entity\Prestataire $prestataire
     *
     * @return Prestation
     */
    public function setPrestataire(\Entity\Prestataire $prestataire = null)
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    /**
     * Get prestataire
     *
     * @return \Entity\Prestataire
     */
    public function getPrestataire()
    {
        return $this->prestataire;
    }

    /**
     * Set visite
     *
     * @param \Entity\Visite $visite
     *
     * @return Prestation
     */
    public function setVisite(\Entity\Visite $visite = null)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite
     *
     * @return \Entity\Visite
     */
    public function getVisite()
    {
        return $this->visite;
    }
}
