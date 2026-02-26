<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * AgenceLangue
 *
 * @ORM\Table(name="dbx45ty_agence_langue", uniqueConstraints={@ORM\UniqueConstraint(name="langue_id_2", columns={"langue_id", "agence_id"})}, indexes={@ORM\Index(name="langue_id", columns={"langue_id"}), @ORM\Index(name="agence_id", columns={"agence_id"})})
 * @ORM\Entity
 */
class AgenceLangue extends Entity
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
     * @ORM\Column(name="description_courte", type="text", length=255, nullable=true)
     */
    protected $descriptionCourte;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    protected $description;

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
     * @var \Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence_id", referencedColumnName="id")
     * })
     */
    protected $agence;



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
     * Set descriptionCourte
     *
     * @param string $descriptionCourte
     *
     * @return AgenceLangue
     */
    public function setDescriptionCourte($descriptionCourte)
    {
        $this->descriptionCourte = $descriptionCourte;

        return $this;
    }

    /**
     * Get descriptionCourte
     *
     * @return string
     */
    public function getDescriptionCourte()
    {
        return $this->descriptionCourte;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AgenceLangue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set langue
     *
     * @param \Entity\Langue $langue
     *
     * @return AgenceLangue
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
     * Set agence
     *
     * @param \Entity\Agence $agence
     *
     * @return AgenceLangue
     */
    public function setAgence(\Entity\Agence $agence = null)
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * Get agence
     *
     * @return \Entity\Agence
     */
    public function getAgence()
    {
        return $this->agence;
    }
}
