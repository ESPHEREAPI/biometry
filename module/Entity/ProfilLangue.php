<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * ProfilLangue
 *
 * @ORM\Table(name="dbx45ty_profil_langue", uniqueConstraints={@ORM\UniqueConstraint(name="langue_id_2", columns={"langue_id", "profil_id"})}, indexes={@ORM\Index(name="langue_id", columns={"langue_id"}), @ORM\Index(name="profil_id", columns={"profil_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\ProfilLangueRepository")
 */
class ProfilLangue extends Entity
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
     * @var \Entity\Profil
     *
     * @ORM\ManyToOne(targetEntity="Entity\Profil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profil_id", referencedColumnName="id")
     * })
     */
    protected $profil;



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
     * @return ProfilLangue
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
     * @return ProfilLangue
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
     * Set profil
     *
     * @param \Entity\Profil $profil
     *
     * @return ProfilLangue
     */
    public function setProfil(\Entity\Profil $profil = null)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \Entity\Profil
     */
    public function getProfil()
    {
        return $this->profil;
    }
}
