<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * TauxPrestation
 *
 * @ORM\Table(name="dbx45ty_taux_prestation", uniqueConstraints={@ORM\UniqueConstraint(name="type_prestation_id_2", columns={"type_prestation_id", "police", "groupe"})}, indexes={@ORM\Index(name="type_prestation_id", columns={"type_prestation_id"})})
 * @ORM\Entity
 */
class TauxPrestation extends Entity
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
     * @ORM\Column(name="police", type="string", length=100, nullable=false)
     */
    protected $police;

    /**
     * @var integer
     *
     * @ORM\Column(name="groupe", type="smallint", nullable=false)
     */
    protected $groupe;

    /**
     * @var float
     *
     * @ORM\Column(name="taux", type="float", precision=10, scale=0, nullable=true)
     */
    protected $taux;

    /**
     * @var float
     *
     * @ORM\Column(name="plafond", type="float", precision=16, scale=0, nullable=true)
     */
    protected $plafond;

    /**
     * @var \Entity\TypePrestation
     *
     * @ORM\ManyToOne(targetEntity="Entity\TypePrestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_prestation_id", referencedColumnName="id")
     * })
     */
    protected $typePrestation;



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
     * Set police
     *
     * @param string $police
     *
     * @return TauxPrestation
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
     * Set groupe
     *
     * @param integer $groupe
     *
     * @return TauxPrestation
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
     * Set taux
     *
     * @param float $taux
     *
     * @return TauxPrestation
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
     * Set plafond
     *
     * @param float $plafond
     *
     * @return TauxPrestation
     */
    public function setPlafond($plafond)
    {
        $this->plafond = $plafond;

        return $this;
    }

    /**
     * Get plafond
     *
     * @return float
     */
    public function getPlafond()
    {
        return $this->plafond;
    }

    /**
     * Set typePrestation
     *
     * @param \Entity\TypePrestation $typePrestation
     *
     * @return TauxPrestation
     */
    public function setTypePrestation(\Entity\TypePrestation $typePrestation = null)
    {
        $this->typePrestation = $typePrestation;

        return $this;
    }

    /**
     * Get typePrestation
     *
     * @return \Entity\TypePrestation
     */
    public function getTypePrestation()
    {
        return $this->typePrestation;
    }
}
