<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Utilisateur
 *
 * @ORM\Table(name="dbx45ty_utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="login_UNIQUE", columns={"login"}), @ORM\UniqueConstraint(name="email", columns={"email"})}, indexes={@ORM\Index(name="langue_defaut", columns={"langue_defaut"})})
 * @ORM\Entity
 */
class Utilisateur extends Entity
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
     * @ORM\Column(name="genre", type="string", nullable=true)
     */
    protected $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    protected $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    protected $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_naissance", type="string", length=255, nullable=true)
     */
    protected $lieuNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=true)
     */
    protected $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone_iso2", type="string", length=4, nullable=true)
     */
    protected $telephoneIso2;

    /**
     * @var integer
     *
     * @ORM\Column(name="telephone_dial_code", type="integer", nullable=true)
     */
    protected $telephoneDialCode;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=150, nullable=false)
     */
    protected $login;

    /**
     * @var string
     *
     * @ORM\Column(name="mot_passe", type="string", length=100, nullable=true)
     */
    protected $motPasse;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", nullable=false)
     */
    protected $supprime = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="newsletter", type="string", nullable=false)
     */
    protected $newsletter = '-1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=false)
     */
    protected $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_provider", type="string", nullable=true)
     */
    protected $oauthProvider;

    /**
     * @var string
     *
     * @ORM\Column(name="oauth_uid", type="string", length=255, nullable=true)
     */
    protected $oauthUid;

    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255, nullable=true)
     */
    protected $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="activite", type="string", length=255, nullable=true)
     */
    protected $activite;

    /**
     * @var string
     *
     * @ORM\Column(name="situation_matrimoniale", type="string", nullable=true)
     */
    protected $situationMatrimoniale;

    /**
     * @var \Entity\Langue
     *
     * @ORM\ManyToOne(targetEntity="Entity\Langue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="langue_defaut", referencedColumnName="id")
     * })
     */
    protected $langueDefaut;



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
     * Set genre
     *
     * @param string $genre
     *
     * @return Utilisateur
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Utilisateur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Utilisateur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set lieuNaissance
     *
     * @param string $lieuNaissance
     *
     * @return Utilisateur
     */
    public function setLieuNaissance($lieuNaissance)
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    /**
     * Get lieuNaissance
     *
     * @return string
     */
    public function getLieuNaissance()
    {
        return $this->lieuNaissance;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Utilisateur
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
     * Set telephoneIso2
     *
     * @param string $telephoneIso2
     *
     * @return Utilisateur
     */
    public function setTelephoneIso2($telephoneIso2)
    {
        $this->telephoneIso2 = $telephoneIso2;

        return $this;
    }

    /**
     * Get telephoneIso2
     *
     * @return string
     */
    public function getTelephoneIso2()
    {
        return $this->telephoneIso2;
    }

    /**
     * Set telephoneDialCode
     *
     * @param integer $telephoneDialCode
     *
     * @return Utilisateur
     */
    public function setTelephoneDialCode($telephoneDialCode)
    {
        $this->telephoneDialCode = $telephoneDialCode;

        return $this;
    }

    /**
     * Get telephoneDialCode
     *
     * @return integer
     */
    public function getTelephoneDialCode()
    {
        return $this->telephoneDialCode;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Utilisateur
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
     * Set login
     *
     * @param string $login
     *
     * @return Utilisateur
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set motPasse
     *
     * @param string $motPasse
     *
     * @return Utilisateur
     */
    public function setMotPasse($motPasse)
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    /**
     * Get motPasse
     *
     * @return string
     */
    public function getMotPasse()
    {
        return $this->motPasse;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Utilisateur
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
     * @return Utilisateur
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
     * Set newsletter
     *
     * @param string $newsletter
     *
     * @return Utilisateur
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return string
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Utilisateur
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
     * Set oauthProvider
     *
     * @param string $oauthProvider
     *
     * @return Utilisateur
     */
    public function setOauthProvider($oauthProvider)
    {
        $this->oauthProvider = $oauthProvider;

        return $this;
    }

    /**
     * Get oauthProvider
     *
     * @return string
     */
    public function getOauthProvider()
    {
        return $this->oauthProvider;
    }

    /**
     * Set oauthUid
     *
     * @param string $oauthUid
     *
     * @return Utilisateur
     */
    public function setOauthUid($oauthUid)
    {
        $this->oauthUid = $oauthUid;

        return $this;
    }

    /**
     * Get oauthUid
     *
     * @return string
     */
    public function getOauthUid()
    {
        return $this->oauthUid;
    }

    /**
     * Set localisation
     *
     * @param string $localisation
     *
     * @return Utilisateur
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set activite
     *
     * @param string $activite
     *
     * @return Utilisateur
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return string
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set situationMatrimoniale
     *
     * @param string $situationMatrimoniale
     *
     * @return Utilisateur
     */
    public function setSituationMatrimoniale($situationMatrimoniale)
    {
        $this->situationMatrimoniale = $situationMatrimoniale;

        return $this;
    }

    /**
     * Get situationMatrimoniale
     *
     * @return string
     */
    public function getSituationMatrimoniale()
    {
        return $this->situationMatrimoniale;
    }

    /**
     * Set langueDefaut
     *
     * @param \Entity\Langue $langueDefaut
     *
     * @return Utilisateur
     */
    public function setLangueDefaut(\Entity\Langue $langueDefaut = null)
    {
        $this->langueDefaut = $langueDefaut;

        return $this;
    }

    /**
     * Get langueDefaut
     *
     * @return \Entity\Langue
     */
    public function getLangueDefaut()
    {
        return $this->langueDefaut;
    }
}
