<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * VilleLangue
 *
 * @ORM\Table(name="dbx45ty_ville_langue", uniqueConstraints={@ORM\UniqueConstraint(name="langue_id_2", columns={"langue_id", "ville_id"})}, indexes={@ORM\Index(name="langue_id", columns={"langue_id"}), @ORM\Index(name="ville_id", columns={"ville_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\VilleLangueRepository")
 */
class VilleLangue extends Entity
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
     * @var \Entity\Ville
     *
     * @ORM\ManyToOne(targetEntity="Entity\Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ville_id", referencedColumnName="id")
     * })
     */
    protected $ville;



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
     * @return VilleLangue
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
     * @return VilleLangue
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
     * Set ville
     *
     * @param \Entity\Ville $ville
     *
     * @return VilleLangue
     */
    public function setVille(\Entity\Ville $ville = null)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }
}
