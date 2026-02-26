<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Employe
 *
 * @ORM\Table(name="dbx45ty_employe", indexes={@ORM\Index(name="fk_employe_utilisateur1_idx", columns={"utilisateur_id"}), @ORM\Index(name="fk_employe_profil1_idx", columns={"profil_id"}), @ORM\Index(name="fk_employe_filiale_agence1_idx", columns={"filiale_agence_id"}), @ORM\Index(name="prestataire_id", columns={"prestataire_id"})})
 * @ORM\Entity
 */
class Employe extends Entity
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
     * @ORM\Column(name="connexion_appli", type="string", nullable=false)
     */
    protected $connexionAppli = '1';

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
     * @var \Entity\FilialeAgence
     *
     * @ORM\ManyToOne(targetEntity="Entity\FilialeAgence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="filiale_agence_id", referencedColumnName="id")
     * })
     */
    protected $filialeAgence;

    /**
     * @var \Entity\Profil
     *
     * @ORM\ManyToOne(targetEntity="Entity\Profil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profil_id", referencedColumnName="id")
     * })
     */
    protected $profil;

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
     * Set connexionAppli
     *
     * @param string $connexionAppli
     *
     * @return Employe
     */
    public function setConnexionAppli($connexionAppli)
    {
        $this->connexionAppli = $connexionAppli;

        return $this;
    }

    /**
     * Get connexionAppli
     *
     * @return string
     */
    public function getConnexionAppli()
    {
        return $this->connexionAppli;
    }

    /**
     * Set prestataire
     *
     * @param \Entity\Prestataire $prestataire
     *
     * @return Employe
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
     * Set filialeAgence
     *
     * @param \Entity\FilialeAgence $filialeAgence
     *
     * @return Employe
     */
    public function setFilialeAgence(\Entity\FilialeAgence $filialeAgence = null)
    {
        $this->filialeAgence = $filialeAgence;

        return $this;
    }

    /**
     * Get filialeAgence
     *
     * @return \Entity\FilialeAgence
     */
    public function getFilialeAgence()
    {
        return $this->filialeAgence;
    }

    /**
     * Set profil
     *
     * @param \Entity\Profil $profil
     *
     * @return Employe
     */
    public function setProfil(\Entity\Profil $profil = null)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \Entity\Profil
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set utilisateur
     *
     * @param \Entity\Utilisateur $utilisateur
     *
     * @return Employe
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
