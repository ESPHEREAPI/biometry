<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consultation
 *
 * @ORM\Table(name="dbx45ty_consultation", uniqueConstraints={@ORM\UniqueConstraint(name="visite_id", columns={"visite_id"})}, indexes={@ORM\Index(name="employe_valide_rejete_id", columns={"employe_valide_rejete_id"}), @ORM\Index(name="type_consultation", columns={"type_consultation"})})
 * @ORM\Entity
 */
class Consultation extends \Custom\Model\Entity
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
     * @var float|null
     *
     * @ORM\Column(name="taux", type="float", precision=10, scale=0, nullable=true)
     */
    protected $taux;

    /**
     * @var string
     *
     * @ORM\Column(name="nature_consultation", type="string", length=0, nullable=false, options={"default"="payante"})
     */
    protected $natureConsultation = 'payante';

    /**
     * @var string|null
     *
     * @ORM\Column(name="nature_affection", type="string", length=255, nullable=true)
     */
    protected $natureAffection;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     */
    protected $montant = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="montant_modif", type="float", precision=10, scale=0, nullable=true)
     */
    protected $montantModif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_valide_rejete", type="datetime", nullable=true)
     */
    protected $dateValideRejete;

    /**
     * @var string|null
     *
     * @ORM\Column(name="observations", type="string", length=255, nullable=true)
     */
    protected $observations;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_consultation", type="string", length=0, nullable=false, options={"default"="attente_validation"})
     */
    protected $etatConsultation = 'attente_validation';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", length=0, nullable=false, options={"default"="-1"})
     */
    protected $supprime = '-1';

    /**
     * @var \Entity\Visite
     *
     * @ORM\ManyToOne(targetEntity="Entity\Visite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visite_id", referencedColumnName="id")
     * })
     */
    protected $visite;

    /**
     * @var \Entity\Employe
     *
     * @ORM\ManyToOne(targetEntity="Entity\Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe_valide_rejete_id", referencedColumnName="id")
     * })
     */
    protected $employeValideRejete;

    /**
     * @var \Entity\TypePrestation
     *
     * @ORM\ManyToOne(targetEntity="Entity\TypePrestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_consultation", referencedColumnName="id")
     * })
     */
    protected $typeConsultation;



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
     * Set taux.
     *
     * @param float|null $taux
     *
     * @return Consultation
     */
    public function setTaux($taux = null)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux.
     *
     * @return float|null
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set natureConsultation.
     *
     * @param string $natureConsultation
     *
     * @return Consultation
     */
    public function setNatureConsultation($natureConsultation)
    {
        $this->natureConsultation = $natureConsultation;

        return $this;
    }

    /**
     * Get natureConsultation.
     *
     * @return string
     */
    public function getNatureConsultation()
    {
        return $this->natureConsultation;
    }

    /**
     * Set natureAffection.
     *
     * @param string|null $natureAffection
     *
     * @return Consultation
     */
    public function setNatureAffection($natureAffection = null)
    {
        $this->natureAffection = $natureAffection;

        return $this;
    }

    /**
     * Get natureAffection.
     *
     * @return string|null
     */
    public function getNatureAffection()
    {
        return $this->natureAffection;
    }

    /**
     * Set montant.
     *
     * @param float $montant
     *
     * @return Consultation
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant.
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set montantModif.
     *
     * @param float|null $montantModif
     *
     * @return Consultation
     */
    public function setMontantModif($montantModif = null)
    {
        $this->montantModif = $montantModif;

        return $this;
    }

    /**
     * Get montantModif.
     *
     * @return float|null
     */
    public function getMontantModif()
    {
        return $this->montantModif;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Consultation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dateValideRejete.
     *
     * @param \DateTime|null $dateValideRejete
     *
     * @return Consultation
     */
    public function setDateValideRejete($dateValideRejete = null)
    {
        $this->dateValideRejete = $dateValideRejete;

        return $this;
    }

    /**
     * Get dateValideRejete.
     *
     * @return \DateTime|null
     */
    public function getDateValideRejete()
    {
        return $this->dateValideRejete;
    }

    /**
     * Set observations.
     *
     * @param string|null $observations
     *
     * @return Consultation
     */
    public function setObservations($observations = null)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations.
     *
     * @return string|null
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set etatConsultation.
     *
     * @param string $etatConsultation
     *
     * @return Consultation
     */
    public function setEtatConsultation($etatConsultation)
    {
        $this->etatConsultation = $etatConsultation;

        return $this;
    }

    /**
     * Get etatConsultation.
     *
     * @return string
     */
    public function getEtatConsultation()
    {
        return $this->etatConsultation;
    }

    /**
     * Set supprime.
     *
     * @param string $supprime
     *
     * @return Consultation
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

    /**
     * Set visite.
     *
     * @param \Entity\Visite|null $visite
     *
     * @return Consultation
     */
    public function setVisite(\Entity\Visite $visite = null)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite.
     *
     * @return \Entity\Visite|null
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set employeValideRejete.
     *
     * @param \Entity\Employe|null $employeValideRejete
     *
     * @return Consultation
     */
    public function setEmployeValideRejete(\Entity\Employe $employeValideRejete = null)
    {
        $this->employeValideRejete = $employeValideRejete;

        return $this;
    }

    /**
     * Get employeValideRejete.
     *
     * @return \Entity\Employe|null
     */
    public function getEmployeValideRejete()
    {
        return $this->employeValideRejete;
    }

    /**
     * Set typeConsultation.
     *
     * @param \Entity\TypePrestation|null $typeConsultation
     *
     * @return Consultation
     */
    public function setTypeConsultation(\Entity\TypePrestation $typeConsultation = null)
    {
        $this->typeConsultation = $typeConsultation;

        return $this;
    }

    /**
     * Get typeConsultation.
     *
     * @return \Entity\TypePrestation|null
     */
    public function getTypeConsultation()
    {
        return $this->typeConsultation;
    }
}
