<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Prestataire
 *
 * @ORM\Table(name="dbx45ty_prestataire", indexes={@ORM\Index(name="categorie", columns={"categorie_id"}), @ORM\Index(name="ville_id", columns={"ville_id"})})
 * @ORM\Entity
 */
class Prestataire extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=100, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=true)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    protected $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=50, nullable=true)
     */
    protected $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="registre", type="string", length=255, nullable=true)
     */
    protected $registre;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    protected $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", nullable=false)
     */
    protected $supprime = '-1';

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
     * @var \Entity\CategoriePrestataire
     *
     * @ORM\ManyToOne(targetEntity="Entity\CategoriePrestataire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * })
     */
    protected $categorie;



    /**
     * Get id
     *
     * @return string
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
     * @return Prestataire
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Prestataire
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Prestataire
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Prestataire
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set registre
     *
     * @param string $registre
     *
     * @return Prestataire
     */
    public function setRegistre($registre)
    {
        $this->registre = $registre;

        return $this;
    }

    /**
     * Get registre
     *
     * @return string
     */
    public function getRegistre()
    {
        return $this->registre;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Prestataire
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Prestataire
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
     * @return Prestataire
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
     * Set ville
     *
     * @param \Entity\Ville $ville
     *
     * @return Prestataire
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

    /**
     * Set categorie
     *
     * @param \Entity\CategoriePrestataire $categorie
     *
     * @return Prestataire
     */
    public function setCategorie(\Entity\CategoriePrestataire $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Entity\CategoriePrestataire
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set id.
     *
     * @param string $id
     *
     * @return Prestataire
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
