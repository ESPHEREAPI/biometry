<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dbx45tyPrestation
 *
 * @ORM\Table(name="dbx45ty_prestation", uniqueConstraints={@ORM\UniqueConstraint(name="visite_id_2", columns={"visite_id", "nature_prestation"})}, indexes={@ORM\Index(name="prestataire_id", columns={"prestataire_id"}), @ORM\Index(name="visite_id", columns={"visite_id"})})
 * @ORM\Entity
 */
class Dbx45tyPrestation extends \Custom\Model\Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nature_prestation", type="string", length=0, nullable=false, options={"comment"="Soit ordonnance, soit exament,soit hospitalisation"})
     */
    private $naturePrestation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", length=0, nullable=false, options={"default"="-1"})
     */
    private $supprime = '-1';

    /**
     * @var \Entity\Dbx45tyVisite
     *
     * @ORM\ManyToOne(targetEntity="Entity\Dbx45tyVisite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visite_id", referencedColumnName="id")
     * })
     */
    private $visite;

    /**
     * @var \Entity\Dbx45tyPrestataire
     *
     * @ORM\ManyToOne(targetEntity="Entity\Dbx45tyPrestataire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prestataire_id", referencedColumnName="id")
     * })
     */
    private $prestataire;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set naturePrestation.
     *
     * @param string $naturePrestation
     *
     * @return Dbx45tyPrestation
     */
    public function setNaturePrestation($naturePrestation)
    {
        $this->naturePrestation = $naturePrestation;

        return $this;
    }

    /**
     * Get naturePrestation.
     *
     * @return string
     */
    public function getNaturePrestation()
    {
        return $this->naturePrestation;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Dbx45tyPrestation
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
     * Set supprime.
     *
     * @param string $supprime
     *
     * @return Dbx45tyPrestation
     */
    public function setSupprime($supprime)
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * Get supprime.
     *
     * @return string
     */
    public function getSupprime()
    {
        return $this->supprime;
    }

    /**
     * Set visite.
     *
     * @param \Entity\Dbx45tyVisite|null $visite
     *
     * @return Dbx45tyPrestation
     */
    public function setVisite(\Entity\Dbx45tyVisite $visite = null)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite.
     *
     * @return \Entity\Dbx45tyVisite|null
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set prestataire.
     *
     * @param \Entity\Dbx45tyPrestataire|null $prestataire
     *
     * @return Dbx45tyPrestation
     */
    public function setPrestataire(\Entity\Dbx45tyPrestataire $prestataire = null)
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    /**
     * Get prestataire.
     *
     * @return \Entity\Dbx45tyPrestataire|null
     */
    public function getPrestataire()
    {
        return $this->prestataire;
    }
}
