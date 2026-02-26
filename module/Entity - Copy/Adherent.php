<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Adherent
 *
 * @ORM\Table(name="dbx45ty_adherent")
 * @ORM\Entity
 */
class Adherent extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="code_adherent", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    protected $codeAdherent;

    /**
     * @var string
     *
     * @ORM\Column(name="assure_principal", type="string", length=255, nullable=true)
     */
    protected $assurePrincipal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="naissance", type="date", nullable=true)
     */
    protected $naissance;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=5, nullable=true)
     */
    protected $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=100, nullable=true)
     */
    protected $matricule;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=false)
     */
    protected $telephone;

    /**
     * @var float
     *
     * @ORM\Column(name="taux", type="float", precision=10, scale=0, nullable=true)
     */
    protected $taux;

    /**
     * @var string
     *
     * @ORM\Column(name="souscripteur", type="string", length=255, nullable=true)
     */
    protected $souscripteur;

    /**
     * @var string
     *
     * @ORM\Column(name="police", type="string", length=100, nullable=true)
     */
    protected $police;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="effet_police", type="date", nullable=true)
     */
    protected $effetPolice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="echeance_police", type="date", nullable=true)
     */
    protected $echeancePolice;

    /**
     * @var integer
     *
     * @ORM\Column(name="groupe", type="smallint", nullable=true)
     */
    protected $groupe;

    /**
     * @var string
     *
     * @ORM\Column(name="enrole", type="string", nullable=false)
     */
    protected $enrole = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="imprime", type="string", nullable=false)
     */
    protected $imprime = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut = '1';



    /**
     * Get codeAdherent
     *
     * @return string
     */
    public function getCodeAdherent()
    {
        return $this->codeAdherent;
    }

    /**
     * Set assurePrincipal
     *
     * @param string $assurePrincipal
     *
     * @return Adherent
     */
    public function setAssurePrincipal($assurePrincipal)
    {
        $this->assurePrincipal = $assurePrincipal;

        return $this;
    }

    /**
     * Get assurePrincipal
     *
     * @return string
     */
    public function getAssurePrincipal()
    {
        return $this->assurePrincipal;
    }

    /**
     * Set naissance
     *
     * @param \DateTime $naissance
     *
     * @return Adherent
     */
    public function setNaissance($naissance)
    {
        $this->naissance = $naissance;

        return $this;
    }

    /**
     * Get naissance
     *
     * @return \DateTime
     */
    public function getNaissance()
    {
        return $this->naissance;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Adherent
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }
	
	 /**
     * Set telephone.
     *
     * @param string $telephone
     *
     * @return Visite
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone.
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set matricule
     *
     * @param string $matricule
     *
     * @return Adherent
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }
	

    /**
     * Set taux
     *
     * @param float $taux
     *
     * @return Adherent
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return float
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set souscripteur
     *
     * @param string $souscripteur
     *
     * @return Adherent
     */
    public function setSouscripteur($souscripteur)
    {
        $this->souscripteur = $souscripteur;

        return $this;
    }

    /**
     * Get souscripteur
     *
     * @return string
     */
    public function getSouscripteur()
    {
        return $this->souscripteur;
    }

    /**
     * Set police
     *
     * @param string $police
     *
     * @return Adherent
     */
    public function setPolice($police)
    {
        $this->police = $police;

        return $this;
    }

    /**
     * Get police
     *
     * @return string
     */
    public function getPolice()
    {
        return $this->police;
    }

    /**
     * Set effetPolice
     *
     * @param \DateTime $effetPolice
     *
     * @return Adherent
     */
    public function setEffetPolice($effetPolice)
    {
        $this->effetPolice = $effetPolice;

        return $this;
    }

    /**
     * Get effetPolice
     *
     * @return \DateTime
     */
    public function getEffetPolice()
    {
        return $this->effetPolice;
    }

    /**
     * Set echeancePolice
     *
     * @param \DateTime $echeancePolice
     *
     * @return Adherent
     */
    public function setEcheancePolice($echeancePolice)
    {
        $this->echeancePolice = $echeancePolice;

        return $this;
    }

    /**
     * Get echeancePolice
     *
     * @return \DateTime
     */
    public function getEcheancePolice()
    {
        return $this->echeancePolice;
    }

    /**
     * Set groupe
     *
     * @param integer $groupe
     *
     * @return Adherent
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return integer
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set enrole
     *
     * @param string $enrole
     *
     * @return Adherent
     */
    public function setEnrole($enrole)
    {
        $this->enrole = $enrole;

        return $this;
    }

    /**
     * Get enrole
     *
     * @return string
     */
    public function getEnrole()
    {
        return $this->enrole;
    }

    /**
     * Set imprime
     *
     * @param string $imprime
     *
     * @return Adherent
     */
    public function setImprime($imprime)
    {
        $this->imprime = $imprime;

        return $this;
    }

    /**
     * Get imprime
     *
     * @return string
     */
    public function getImprime()
    {
        return $this->imprime;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Adherent
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
     * Set codeAdherent.
     *
     * @param string $codeAdherent
     *
     * @return Adherent
     */
    public function setCodeAdherent($codeAdherent)
    {
        $this->codeAdherent = $codeAdherent;

        return $this;
    }
}
