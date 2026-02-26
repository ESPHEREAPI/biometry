<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * ConsultationAudit
 *
 * @ORM\Table(name="dbx45ty_consultation_audit", indexes={@ORM\Index(name="consultation_id", columns={"consultation_id"}), @ORM\Index(name="employe_id", columns={"employe_id"})})
 * @ORM\Entity
 */
class ConsultationAudit extends Entity
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
     * @ORM\Column(name="etat_consultation", type="string", nullable=false)
     */
    protected $etatConsultation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @var \Entity\Employe
     *
     * @ORM\ManyToOne(targetEntity="Entity\Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe_id", referencedColumnName="id")
     * })
     */
    protected $employe;

    /**
     * @var \Entity\Consultation
     *
     * @ORM\ManyToOne(targetEntity="Entity\Consultation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="consultation_id", referencedColumnName="id")
     * })
     */
    protected $consultation;



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
     * Set etatConsultation
     *
     * @param string $etatConsultation
     *
     * @return ConsultationAudit
     */
    public function setEtatConsultation($etatConsultation)
    {
        $this->etatConsultation = $etatConsultation;

        return $this;
    }

    /**
     * Get etatConsultation
     *
     * @return string
     */
    public function getEtatConsultation()
    {
        return $this->etatConsultation;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ConsultationAudit
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set employe
     *
     * @param \Entity\Employe $employe
     *
     * @return ConsultationAudit
     */
    public function setEmploye(\Entity\Employe $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \Entity\Employe
     */
    public function getEmploye()
    {
        return $this->employe;
    }

    /**
     * Set consultation
     *
     * @param \Entity\Consultation $consultation
     *
     * @return ConsultationAudit
     */
    public function setConsultation(\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
    }
}
