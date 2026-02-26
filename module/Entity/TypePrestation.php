<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Dbx45tyTypePrestation
 *
 * @ORM\Table(name="dbx45ty_type_prestation")
 * @ORM\Entity
 */
class TypePrestation extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=5, nullable=false)
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
     * @var int
     *
     * @ORM\Column(name="affiche", type="integer", nullable=false, options={"comment"="0=pas affiche,1=affiche"})
     */
    protected $affiche;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=0, nullable=false)
     */
    protected $categorie;



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
     * Set nom.
     *
     * @param string $nom
     *
     * @return Dbx45tyTypePrestation
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
     * Set affiche.
     *
     * @param int $affiche
     *
     * @return Dbx45tyTypePrestation
     */
    public function setAffiche($affiche)
    {
        $this->affiche = $affiche;

        return $this;
    }

    /**
     * Get affiche.
     *
     * @return int
     */
    public function getAffiche()
    {
        return $this->affiche;
    }

    /**
     * Set categorie.
     *
     * @param string $categorie
     *
     * @return Dbx45tyTypePrestation
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie.
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}
