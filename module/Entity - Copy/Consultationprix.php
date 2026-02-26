<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consultationprix
 *
 * @ORM\Table(name="dbx45ty_consultationprix")
 * @ORM\Entity
 */
class Consultationprix extends \Custom\Model\Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    protected $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=0, nullable=false, options={"default"="1"})
     */
    protected $statut = '1';
	
	
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return Consultationprix
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Consultationprix
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prix.
     *
     * @param float|null $prix
     *
     * @return Consultationprix
     */
    public function setPrix($prix = null)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return float|null
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set statut.
     *
     * @param string $statut
     *
     * @return Consultationprix
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
     * Set supprime.
     *
     * @param string $supprime
     *
     * @return Consultationprix
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
}
