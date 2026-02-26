<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Profil
 *
 * @ORM\Table(name="dbx45ty_profil", uniqueConstraints={@ORM\UniqueConstraint(name="code_UNIQUE", columns={"code"})})
 * @ORM\Entity
 */
class Profil extends Entity
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
     * @ORM\Column(name="type_profil", type="string", nullable=false)
     */
    protected $typeProfil = 'prestataire';

    /**
     * @var string
     *
     * @ORM\Column(name="type_sous_profil", type="string", nullable=false)
     */
    protected $typeSousProfil;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", nullable=false)
     */
    protected $supprime = '-1';



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
     * Set typeProfil
     *
     * @param string $typeProfil
     *
     * @return Profil
     */
    public function setTypeProfil($typeProfil)
    {
        $this->typeProfil = $typeProfil;

        return $this;
    }

    /**
     * Get typeProfil
     *
     * @return string
     */
    public function getTypeProfil()
    {
        return $this->typeProfil;
    }

    /**
     * Set typeSousProfil
     *
     * @param string $typeSousProfil
     *
     * @return Profil
     */
    public function setTypeSousProfil($typeSousProfil)
    {
        $this->typeSousProfil = $typeSousProfil;

        return $this;
    }

    /**
     * Get typeSousProfil
     *
     * @return string
     */
    public function getTypeSousProfil()
    {
        return $this->typeSousProfil;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Profil
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
     * @return Profil
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
     * @return Profil
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
}
