<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LignePrestation
 *
 * @ORM\Table(name="dbx45ty_ligne_prestation", indexes={@ORM\Index(name="prestataire_id", columns={"prestataire_id"}), @ORM\Index(name="type_examen", columns={"type_examen"}), @ORM\Index(name="medicament_id", columns={"medicament_id"}), @ORM\Index(name="examen_id", columns={"examen_id"}), @ORM\Index(name="prestation_id", columns={"prestation_id"}), @ORM\Index(name="employe_valide_rejete_id", columns={"employe_valide_rejete_id"}), @ORM\Index(name="description_soins", columns={"description_soins"})})
 * @ORM\Entity
 */
class LignePrestation extends \Custom\Model\Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
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
     * @var string|null
     *
     * @ORM\Column(name="dents_concernees", type="string", length=255, nullable=true)
     */
    protected $dentsConcernees;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codification", type="string", length=255, nullable=true)
     */
    protected $codification;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected $nom;

    /**
     * @var float|null
     *
     * @ORM\Column(name="valeur", type="float", precision=10, scale=0, nullable=true)
     */
    protected $valeur;

    /**
     * @var float|null
     *
     * @ORM\Column(name="nbre", type="float", precision=10, scale=0, nullable=true)
     */
    protected $nbre;

    /**
     * @var float
     *
     * @ORM\Column(name="acte_prelevement", type="float", precision=10, scale=0, nullable=false)
     */
    protected $actePrelevement = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="valeur_modif", type="float", precision=10, scale=0, nullable=true)
     */
    protected $valeurModif;

    /**
     * @var float|null
     *
     * @ORM\Column(name="nbre_modif", type="float", precision=10, scale=0, nullable=true)
     */
    protected $nbreModif;

    /**
     * @var float
     *
     * @ORM\Column(name="acte_prelevement_modif", type="float", precision=10, scale=0, nullable=false)
     */
    protected $actePrelevementModif = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="posologie", type="string", length=255, nullable=true)
     */
    protected $posologie;

    /**
     * @var string|null
     *
     * @ORM\Column(name="observations", type="string", length=255, nullable=true)
     */
    protected $observations;

    /**
     * @var string|null
     *
     * @ORM\Column(name="observations_acte_prelevement", type="string", length=255, nullable=true)
     */
    protected $observationsActePrelevement;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_encaisse", type="datetime", nullable=true)
     */
    protected $dateEncaisse;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=0, nullable=false, options={"default"="enregistre"})
     */
    protected $etat = 'enregistre';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", length=0, nullable=false, options={"default"="-1"})
     */
    protected $supprime = '-1';

    /**
     * @var \Entity\Prestation
     *
     * @ORM\ManyToOne(targetEntity="Entity\Prestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prestation_id", referencedColumnName="id")
     * })
     */
    protected $prestation;

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
     *   @ORM\JoinColumn(name="type_examen", referencedColumnName="id")
     * })
     */
    protected $typeExamen;

    /**
     * @var \Entity\TypePrestation
     *
     * @ORM\ManyToOne(targetEntity="Entity\TypePrestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="description_soins", referencedColumnName="id")
     * })
     */
    protected $descriptionSoins;

    /**
     * @var \Entity\Medicament
     *
     * @ORM\ManyToOne(targetEntity="Entity\Medicament")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="medicament_id", referencedColumnName="id")
     * })
     */
    protected $medicament;

    /**
     * @var \Entity\Examen
     *
     * @ORM\ManyToOne(targetEntity="Entity\Examen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="examen_id", referencedColumnName="id")
     * })
     */
    protected $examen;



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
     * @return LignePrestation
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
     * Set dentsConcernees.
     *
     * @param string|null $dentsConcernees
     *
     * @return LignePrestation
     */
    public function setDentsConcernees($dentsConcernees = null)
    {
        $this->dentsConcernees = $dentsConcernees;

        return $this;
    }

    /**
     * Get dentsConcernees.
     *
     * @return string|null
     */
    public function getDentsConcernees()
    {
        return $this->dentsConcernees;
    }

    /**
     * Set codification.
     *
     * @param string|null $codification
     *
     * @return LignePrestation
     */
    public function setCodification($codification = null)
    {
        $this->codification = $codification;

        return $this;
    }

    /**
     * Get codification.
     *
     * @return string|null
     */
    public function getCodification()
    {
        return $this->codification;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return LignePrestation
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set valeur.
     *
     * @param float|null $valeur
     *
     * @return LignePrestation
     */
    public function setValeur($valeur = null)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur.
     *
     * @return float|null
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set nbre.
     *
     * @param float|null $nbre
     *
     * @return LignePrestation
     */
    public function setNbre($nbre = null)
    {
        $this->nbre = $nbre;

        return $this;
    }

    /**
     * Get nbre.
     *
     * @return float|null
     */
    public function getNbre()
    {
        return $this->nbre;
    }

    /**
     * Set actePrelevement.
     *
     * @param float $actePrelevement
     *
     * @return LignePrestation
     */
    public function setActePrelevement($actePrelevement)
    {
        $this->actePrelevement = $actePrelevement;

        return $this;
    }

    /**
     * Get actePrelevement.
     *
     * @return float
     */
    public function getActePrelevement()
    {
        return $this->actePrelevement;
    }

    /**
     * Set valeurModif.
     *
     * @param float|null $valeurModif
     *
     * @return LignePrestation
     */
    public function setValeurModif($valeurModif = null)
    {
        $this->valeurModif = $valeurModif;

        return $this;
    }

    /**
     * Get valeurModif.
     *
     * @return float|null
     */
    public function getValeurModif()
    {
        return $this->valeurModif;
    }

    /**
     * Set nbreModif.
     *
     * @param float|null $nbreModif
     *
     * @return LignePrestation
     */
    public function setNbreModif($nbreModif = null)
    {
        $this->nbreModif = $nbreModif;

        return $this;
    }

    /**
     * Get nbreModif.
     *
     * @return float|null
     */
    public function getNbreModif()
    {
        return $this->nbreModif;
    }

    /**
     * Set actePrelevementModif.
     *
     * @param float $actePrelevementModif
     *
     * @return LignePrestation
     */
    public function setActePrelevementModif($actePrelevementModif)
    {
        $this->actePrelevementModif = $actePrelevementModif;

        return $this;
    }

    /**
     * Get actePrelevementModif.
     *
     * @return float
     */
    public function getActePrelevementModif()
    {
        return $this->actePrelevementModif;
    }

    /**
     * Set posologie.
     *
     * @param string|null $posologie
     *
     * @return LignePrestation
     */
    public function setPosologie($posologie = null)
    {
        $this->posologie = $posologie;

        return $this;
    }

    /**
     * Get posologie.
     *
     * @return string|null
     */
    public function getPosologie()
    {
        return $this->posologie;
    }

    /**
     * Set observations.
     *
     * @param string|null $observations
     *
     * @return LignePrestation
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
     * Set observationsActePrelevement.
     *
     * @param string|null $observationsActePrelevement
     *
     * @return LignePrestation
     */
    public function setObservationsActePrelevement($observationsActePrelevement = null)
    {
        $this->observationsActePrelevement = $observationsActePrelevement;

        return $this;
    }

    /**
     * Get observationsActePrelevement.
     *
     * @return string|null
     */
    public function getObservationsActePrelevement()
    {
        return $this->observationsActePrelevement;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return LignePrestation
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
     * @return LignePrestation
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
     * Set dateEncaisse.
     *
     * @param \DateTime|null $dateEncaisse
     *
     * @return LignePrestation
     */
    public function setDateEncaisse($dateEncaisse = null)
    {
        $this->dateEncaisse = $dateEncaisse;

        return $this;
    }

    /**
     * Get dateEncaisse.
     *
     * @return \DateTime|null
     */
    public function getDateEncaisse()
    {
        return $this->dateEncaisse;
    }

    /**
     * Set etat.
     *
     * @param string $etat
     *
     * @return LignePrestation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat.
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set supprime.
     *
     * @param string $supprime
     *
     * @return LignePrestation
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
     * Set prestation.
     *
     * @param \Entity\Prestation|null $prestation
     *
     * @return LignePrestation
     */
    public function setPrestation(\Entity\Prestation $prestation = null)
    {
        $this->prestation = $prestation;

        return $this;
    }

    /**
     * Get prestation.
     *
     * @return \Entity\Prestation|null
     */
    public function getPrestation()
    {
        return $this->prestation;
    }

    /**
     * Set prestataire.
     *
     * @param \Entity\Prestataire|null $prestataire
     *
     * @return LignePrestation
     */
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

    /**
     * Set employeValideRejete.
     *
     * @param \Entity\Employe|null $employeValideRejete
     *
     * @return LignePrestation
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
     * Set typeExamen.
     *
     * @param \Entity\TypePrestation|null $typeExamen
     *
     * @return LignePrestation
     */
    public function setTypeExamen(\Entity\TypePrestation $typeExamen = null)
    {
        $this->typeExamen = $typeExamen;

        return $this;
    }

    /**
     * Get typeExamen.
     *
     * @return \Entity\TypePrestation|null
     */
    public function getTypeExamen()
    {
        return $this->typeExamen;
    }

    /**
     * Set descriptionSoins.
     *
     * @param \Entity\TypePrestation|null $descriptionSoins
     *
     * @return LignePrestation
     */
    public function setDescriptionSoins(\Entity\TypePrestation $descriptionSoins = null)
    {
        $this->descriptionSoins = $descriptionSoins;

        return $this;
    }

    /**
     * Get descriptionSoins.
     *
     * @return \Entity\TypePrestation|null
     */
    public function getDescriptionSoins()
    {
        return $this->descriptionSoins;
    }

    /**
     * Set medicament.
     *
     * @param \Entity\Medicament|null $medicament
     *
     * @return LignePrestation
     */
    public function setMedicament(\Entity\Medicament $medicament = null)
    {
        $this->medicament = $medicament;

        return $this;
    }

    /**
     * Get medicament.
     *
     * @return \Entity\Medicament|null
     */
    public function getMedicament()
    {
        return $this->medicament;
    }

    /**
     * Set examen.
     *
     * @param \Entity\Examen|null $examen
     *
     * @return LignePrestation
     */
    public function setExamen(\Entity\Examen $examen = null)
    {
        $this->examen = $examen;

        return $this;
    }

    /**
     * Get examen.
     *
     * @return \Entity\Examen|null
     */
    public function getExamen()
    {
        return $this->examen;
    }
}
