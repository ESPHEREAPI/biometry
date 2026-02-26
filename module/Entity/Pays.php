<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Pays
 *
 * @ORM\Table(name="dbx45ty_pays", uniqueConstraints={@ORM\UniqueConstraint(name="code_iso_UNIQUE", columns={"code_iso"})}, indexes={@ORM\Index(name="fk_pays_zone_monde_idx", columns={"zone_monde_id"}), @ORM\Index(name="fk_pays_capitale1_idx", columns={"capitale_id"})})
 * @ORM\Entity
 */
class Pays extends Entity
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
     * @ORM\Column(name="code_iso", type="string", length=10, nullable=true)
     */
    protected $codeIso;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=10, nullable=true)
     */
    protected $codePostal;

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
     * @var \Entity\ZoneMonde
     *
     * @ORM\ManyToOne(targetEntity="Entity\ZoneMonde")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="zone_monde_id", referencedColumnName="id")
     * })
     */
    protected $zoneMonde;

    /**
     * @var \Entity\Ville
     *
     * @ORM\ManyToOne(targetEntity="Entity\Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="capitale_id", referencedColumnName="id")
     * })
     */
    protected $capitale;



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
     * Set codeIso
     *
     * @param string $codeIso
     *
     * @return Pays
     */
    public function setCodeIso($codeIso)
    {
        $this->codeIso = $codeIso;

        return $this;
    }

    /**
     * Get codeIso
     *
     * @return string
     */
    public function getCodeIso()
    {
        return $this->codeIso;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Pays
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Pays
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
     * @return Pays
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
     * Set zoneMonde
     *
     * @param \Entity\ZoneMonde $zoneMonde
     *
     * @return Pays
     */
    public function setZoneMonde(\Entity\ZoneMonde $zoneMonde = null)
    {
        $this->zoneMonde = $zoneMonde;

        return $this;
    }

    /**
     * Get zoneMonde
     *
     * @return \Entity\ZoneMonde
     */
    public function getZoneMonde()
    {
        return $this->zoneMonde;
    }

    /**
     * Set capitale
     *
     * @param \Entity\Ville $capitale
     *
     * @return Pays
     */
    public function setCapitale(\Entity\Ville $capitale = null)
    {
        $this->capitale = $capitale;

        return $this;
    }

    /**
     * Get capitale
     *
     * @return \Entity\Ville
     */
    public function getCapitale()
    {
        return $this->capitale;
    }
}
