<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * FilialeAgence
 *
 * @ORM\Table(name="dbx45ty_filiale_agence", indexes={@ORM\Index(name="fk_filiale_agence_agence1_idx", columns={"agence_id"}), @ORM\Index(name="fk_filiale_agence_ville1_idx", columns={"ville_id"})})
 * @ORM\Entity
 */
class FilialeAgence extends Entity
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date", nullable=true)
     */
    protected $dateCreation;

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
     * @var \Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence_id", referencedColumnName="id")
     * })
     */
    protected $agence;

    /**
     * @var \Entity\Ville
     *
     * @ORM\ManyToOne(targetEntity="Entity\Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ville_id", referencedColumnName="id")
     * })
     */
    protected $ville;



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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return FilialeAgence
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return FilialeAgence
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
     * @return FilialeAgence
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
     * Set agence
     *
     * @param \Entity\Agence $agence
     *
     * @return FilialeAgence
     */
    public function setAgence(\Entity\Agence $agence = null)
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * Get agence
     *
     * @return \Entity\Agence
     */
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * Set ville
     *
     * @param \Entity\Ville $ville
     *
     * @return FilialeAgence
     */
    public function setVille(\Entity\Ville $ville = null)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }
}
