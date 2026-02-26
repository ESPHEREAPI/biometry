<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medicament
 *
 * @ORM\Table(name="dbx45ty_medicament")
 * @ORM\Entity
 */
class Medicament extends \Custom\Model\Entity
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
     * @var string
     *
     * @ORM\Column(name="origine", type="string", length=255, nullable=true)
     */
    protected $origine;


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
	
	/**
     * @var float|null
     *
     * @ORM\Column(name="quantite", type="float", precision=10, scale=0, nullable=true)
     */
    protected $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=0, nullable=false, options={"default"="1"})
     */
    protected $categorie = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", length=0, nullable=false, options={"default"="-1"})
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
     * @return Medicament
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
     * @return Medicament
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
     * Set origine.
     *
     * @param string $origine
     *
     * @return Medicament
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;

        return $this;
    }

    /**
     * Get origine.
     *
     * @return string
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * Set prix.
     *
     * @param float|null $prix
     *
     * @return Medicament
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
     * @return Medicament
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
     * Set quantite.
     *
     * @param float|null $quantite
     *
     * @return Medicament
     */
    public function setQuantite($quantite = null)
    {
        $this->quantite= $quantite;

        return $this;
    }

    /**
     * Get quantite.
     *
     * @return float|null
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set categorie.
     *
     * @param string $categorie
     *
     * @return Medicament
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set supprime.
     *
     * @param string $supprime
     *
     * @return Medicament
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
