<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Region
 *
 * @ORM\Table(name="dbx45ty_region", uniqueConstraints={@ORM\UniqueConstraint(name="code_pays_unique", columns={"code", "pays_id"})}, indexes={@ORM\Index(name="fk_region_pays1_idx", columns={"pays_id"}), @ORM\Index(name="fk_region_ville1_idx", columns={"chef_lieu_id"})})
 * @ORM\Entity
 */
class Region extends Entity
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
     * @var \Entity\Pays
     *
     * @ORM\ManyToOne(targetEntity="Entity\Pays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pays_id", referencedColumnName="id")
     * })
     */
    protected $pays;

    /**
     * @var \Entity\Ville
     *
     * @ORM\ManyToOne(targetEntity="Entity\Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chef_lieu_id", referencedColumnName="id")
     * })
     */
    protected $chefLieu;



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
     * @return Region
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
     * Set statut
     *
     * @param string $statut
     *
     * @return Region
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
     * @return Region
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
     * Set pays
     *
     * @param \Entity\Pays $pays
     *
     * @return Region
     */
    public function setPays(\Entity\Pays $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \Entity\Pays
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set chefLieu
     *
     * @param \Entity\Ville $chefLieu
     *
     * @return Region
     */
    public function setChefLieu(\Entity\Ville $chefLieu = null)
    {
        $this->chefLieu = $chefLieu;

        return $this;
    }

    /**
     * Get chefLieu
     *
     * @return \Entity\Ville
     */
    public function getChefLieu()
    {
        return $this->chefLieu;
    }
}
