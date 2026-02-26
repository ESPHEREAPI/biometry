<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Ville
 *
 * @ORM\Table(name="dbx45ty_ville", uniqueConstraints={@ORM\UniqueConstraint(name="code_region_UNIQUE", columns={"code", "region_id"})}, indexes={@ORM\Index(name="fk_ville_region1_idx", columns={"region_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\VilleRepository")
 */
class Ville extends Entity
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
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="code_zone", type="string", length=5, nullable=true)
     */
    protected $codeZone;

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
     * Set code
     *
     * @param string $code
     *
     * @return Ville
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set codeZone
     *
     * @param string $codeZone
     *
     * @return Ville
     */
    public function setCodeZone($codeZone)
    {
        $this->codeZone = $codeZone;

        return $this;
    }

    /**
     * Get codeZone
     *
     * @return string
     */
    public function getCodeZone()
    {
        return $this->codeZone;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Ville
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
     * @return Ville
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
     * Set region
     *
     * @param \Entity\Region $region
     *
     * @return Ville
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
