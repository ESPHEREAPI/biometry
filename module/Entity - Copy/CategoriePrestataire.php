<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * CategoriePrestataire
 *
 * @ORM\Table(name="dbx45ty_categorie_prestataire")
 * @ORM\Entity
 */
class CategoriePrestataire extends Entity
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut = '1';



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
     * @return CategoriePrestataire
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
     * Set statut
     *
     * @param string $statut
     *
     * @return CategoriePrestataire
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
     * Set id.
     *
     * @param string $id
     *
     * @return CategoriePrestataire
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
