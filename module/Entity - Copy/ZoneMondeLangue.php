<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * ZoneMondeLangue
 *
 * @ORM\Table(name="dbx45ty_zone_monde_langue", uniqueConstraints={@ORM\UniqueConstraint(name="langue_id_2", columns={"langue_id", "zone_monde_id"})}, indexes={@ORM\Index(name="langue_id", columns={"langue_id"}), @ORM\Index(name="zone_monde_id", columns={"zone_monde_id"})})
 * @ORM\Entity
 */
class ZoneMondeLangue extends Entity
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var \Entity\Langue
     *
     * @ORM\ManyToOne(targetEntity="Entity\Langue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="langue_id", referencedColumnName="id")
     * })
     */
    protected $langue;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return ZoneMondeLangue
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
     * Set langue
     *
     * @param \Entity\Langue $langue
     *
     * @return ZoneMondeLangue
     */
    public function setLangue(\Entity\Langue $langue = null)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return \Entity\Langue
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set zoneMonde
     *
     * @param \Entity\ZoneMonde $zoneMonde
     *
     * @return ZoneMondeLangue
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
}
