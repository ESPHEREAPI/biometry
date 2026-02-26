<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Internaute
 *
 * @ORM\Table(name="dbx45ty_internaute", indexes={@ORM\Index(name="fk_internaute_utilisateur1_idx", columns={"utilisateur_id"})})
 * @ORM\Entity
 */
class Internaute extends Entity
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
     * @ORM\Column(name="token_activation", type="string", length=255, nullable=true)
     */
    protected $tokenActivation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_token_activation", type="datetime", nullable=true)
     */
    protected $dateTokenActivation;

    /**
     * @var string
     *
     * @ORM\Column(name="token_mot_passe_oublie", type="string", length=255, nullable=true)
     */
    protected $tokenMotPasseOublie;

    /**
     * @var string
     *
     * @ORM\Column(name="lien_activer_compte", type="string", length=255, nullable=true)
     */
    protected $lienActiverCompte;

    /**
     * @var \Entity\Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Entity\Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     * })
     */
    protected $utilisateur;



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
     * Set tokenActivation
     *
     * @param string $tokenActivation
     *
     * @return Internaute
     */
    public function setTokenActivation($tokenActivation)
    {
        $this->tokenActivation = $tokenActivation;

        return $this;
    }

    /**
     * Get tokenActivation
     *
     * @return string
     */
    public function getTokenActivation()
    {
        return $this->tokenActivation;
    }

    /**
     * Set dateTokenActivation
     *
     * @param \DateTime $dateTokenActivation
     *
     * @return Internaute
     */
    public function setDateTokenActivation($dateTokenActivation)
    {
        $this->dateTokenActivation = $dateTokenActivation;

        return $this;
    }

    /**
     * Get dateTokenActivation
     *
     * @return \DateTime
     */
    public function getDateTokenActivation()
    {
        return $this->dateTokenActivation;
    }

    /**
     * Set tokenMotPasseOublie
     *
     * @param string $tokenMotPasseOublie
     *
     * @return Internaute
     */
    public function setTokenMotPasseOublie($tokenMotPasseOublie)
    {
        $this->tokenMotPasseOublie = $tokenMotPasseOublie;

        return $this;
    }

    /**
     * Get tokenMotPasseOublie
     *
     * @return string
     */
    public function getTokenMotPasseOublie()
    {
        return $this->tokenMotPasseOublie;
    }

    /**
     * Set lienActiverCompte
     *
     * @param string $lienActiverCompte
     *
     * @return Internaute
     */
    public function setLienActiverCompte($lienActiverCompte)
    {
        $this->lienActiverCompte = $lienActiverCompte;

        return $this;
    }

    /**
     * Get lienActiverCompte
     *
     * @return string
     */
    public function getLienActiverCompte()
    {
        return $this->lienActiverCompte;
    }

    /**
     * Set utilisateur
     *
     * @param \Entity\Utilisateur $utilisateur
     *
     * @return Internaute
     */
    public function setUtilisateur(\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
