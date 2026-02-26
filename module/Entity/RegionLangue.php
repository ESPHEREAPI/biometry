<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * RegionLangue
 *
 * @ORM\Table(name="dbx45ty_region_langue", uniqueConstraints={@ORM\UniqueConstraint(name="langue_id_2", columns={"langue_id", "region_id"})}, indexes={@ORM\Index(name="langue_id", columns={"langue_id"}), @ORM\Index(name="region_id", columns={"region_id"})})
 * @ORM\Entity
 */
class RegionLangue extends Entity
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
     * @var \Entity\Region
     *
     * @ORM\ManyToOne(targetEntity="Entity\Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    protected $region;



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
     * @return RegionLangue
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
     * @return RegionLangue
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
     * Set region
     *
     * @param \Entity\Region $region
     *
     * @return RegionLangue
     */
    public function setRegion(\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }
}
