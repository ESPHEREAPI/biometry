<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Permission
 *
 * @ORM\Table(name="dbx45ty_permission", uniqueConstraints={@ORM\UniqueConstraint(name="profil_id_2", columns={"profil_id", "menu_id"})}, indexes={@ORM\Index(name="profil_id", columns={"profil_id"}), @ORM\Index(name="menu_id", columns={"menu_id"})})
 * @ORM\Entity
 */
class Permission extends Entity
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
     * @var \Entity\Profil
     *
     * @ORM\ManyToOne(targetEntity="Entity\Profil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profil_id", referencedColumnName="id")
     * })
     */
    protected $profil;

    /**
     * @var \Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * })
     */
    protected $menu;



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
     * Set profil
     *
     * @param \Entity\Profil $profil
     *
     * @return Permission
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

    /**
     * Set menu
     *
     * @param \Entity\Menu $menu
     *
     * @return Permission
     */
    public function setMenu(\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
