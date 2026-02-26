<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * LangueHasPays
 *
 * @ORM\Table(name="dbx45ty_langue_has_pays", indexes={@ORM\Index(name="fk_langue_pays_pays1_idx", columns={"pays_id"}), @ORM\Index(name="fk_langue_pays_langue1_idx", columns={"langue_id"})})
 * @ORM\Entity
 */
class LangueHasPays extends Entity
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
     * @var \Entity\Langue
     *
     * @ORM\ManyToOne(targetEntity="Entity\Langue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="langue_id", referencedColumnName="id")
     * })
     */
    protected $langue;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set langue
     *
     * @param \Entity\Langue $langue
     *
     * @return LangueHasPays
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
     * Set pays
     *
     * @param \Entity\Pays $pays
     *
     * @return LangueHasPays
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
}
