<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * MenuLangue
 *
 * @ORM\Table(name="dbx45ty_menu_langue", uniqueConstraints={@ORM\UniqueConstraint(name="langue_id_2", columns={"langue_id", "menu_id"})}, indexes={@ORM\Index(name="langue_id", columns={"langue_id"}), @ORM\Index(name="menu_id", columns={"menu_id"})})
 * @ORM\Entity
 */
class MenuLangue extends Entity
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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_courte", type="string", length=255, nullable=true)
     */
    protected $descCourte;

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
     * Set url
     *
     * @param string $url
     *
     * @return MenuLangue
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return MenuLangue
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
     * Set descCourte
     *
     * @param string $descCourte
     *
     * @return MenuLangue
     */
    public function setDescCourte($descCourte)
    {
        $this->descCourte = $descCourte;

        return $this;
    }

    /**
     * Get descCourte
     *
     * @return string
     */
    public function getDescCourte()
    {
        return $this->descCourte;
    }

    /**
     * Set langue
     *
     * @param \Entity\Langue $langue
     *
     * @return MenuLangue
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
     * Set menu
     *
     * @param \Entity\Menu $menu
     *
     * @return MenuLangue
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
