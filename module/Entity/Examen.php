<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examen
 *
 * @ORM\Table(name="dbx45ty_examen")
 * @ORM\Entity
 */
class Examen extends \Custom\Model\Entity
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="cotation", type="smallint", nullable=false, options={"unsigned"=true})
     */
    protected $cotation;

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
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", length=0, nullable=false, options={"default"="-1"})
     */
    protected $supprime = '-1';



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
     * @param string $code
     *
     * @return Examen
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
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
     * @return Examen
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
     * Set cotation.
     *
     * @param int $cotation
     *
     * @return Examen
     */
    public function setCotation($cotation)
    {
        $this->cotation = $cotation;

        return $this;
    }

    /**
     * Get cotation.
     *
     * @return int
     */
    public function getCotation()
    {
        return $this->cotation;
    }

    /**
     * Set prix.
     *
     * @param float|null $prix
     *
     * @return Examen
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
     * @return Examen
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
     * @return Examen
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
}
