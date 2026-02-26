<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Custom\Model\Entity;

/**
 * Menu
 *
 * @ORM\Table(name="dbx45ty_menu", uniqueConstraints={@ORM\UniqueConstraint(name="pere_id_2", columns={"pere_id", "position", "numero_ordre"}), @ORM\UniqueConstraint(name="nom_controlleur", columns={"nom_controlleur", "nom_module", "nom_action", "numero_ordre"})}, indexes={@ORM\Index(name="pere_id", columns={"pere_id"})})
 * @ORM\Entity
 */
class Menu extends Entity
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
     * @ORM\Column(name="nom_controlleur", type="string", length=150, nullable=true)
     */
    protected $nomControlleur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_module", type="string", length=150, nullable=false)
     */
    protected $nomModule;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_action", type="string", length=150, nullable=true)
     */
    protected $nomAction;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_ordre", type="smallint", nullable=false)
     */
    protected $numeroOrdre = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="class_image", type="string", length=100, nullable=true)
     */
    protected $classImage;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="smallint", nullable=false)
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(name="apparait_nav", type="string", nullable=false)
     */
    protected $apparaitNav;

    /**
     * @var string
     *
     * @ORM\Column(name="apparait_nav_bar", type="string", nullable=false)
     */
    protected $apparaitNavBar;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", nullable=false)
     */
    protected $statut = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="supprime", type="string", nullable=false)
     */
    protected $supprime = '-1';

    /**
     * @var string
     *
     * @ORM\Column(name="chemin_pere", type="text", length=65535, nullable=true)
     */
    protected $cheminPere;

    /**
     * @var \Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pere_id", referencedColumnName="id")
     * })
     */
    protected $pere;



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
     * Set nomControlleur
     *
     * @param string $nomControlleur
     *
     * @return Menu
     */
    public function setNomControlleur($nomControlleur)
    {
        $this->nomControlleur = $nomControlleur;

        return $this;
    }

    /**
     * Get nomControlleur
     *
     * @return string
     */
    public function getNomControlleur()
    {
        return $this->nomControlleur;
    }

    /**
     * Set nomModule
     *
     * @param string $nomModule
     *
     * @return Menu
     */
    public function setNomModule($nomModule)
    {
        $this->nomModule = $nomModule;

        return $this;
    }

    /**
     * Get nomModule
     *
     * @return string
     */
    public function getNomModule()
    {
        return $this->nomModule;
    }

    /**
     * Set nomAction
     *
     * @param string $nomAction
     *
     * @return Menu
     */
    public function setNomAction($nomAction)
    {
        $this->nomAction = $nomAction;

        return $this;
    }

    /**
     * Get nomAction
     *
     * @return string
     */
    public function getNomAction()
    {
        return $this->nomAction;
    }

    /**
     * Set numeroOrdre
     *
     * @param integer $numeroOrdre
     *
     * @return Menu
     */
    public function setNumeroOrdre($numeroOrdre)
    {
        $this->numeroOrdre = $numeroOrdre;

        return $this;
    }

    /**
     * Get numeroOrdre
     *
     * @return integer
     */
    public function getNumeroOrdre()
    {
        return $this->numeroOrdre;
    }

    /**
     * Set classImage
     *
     * @param string $classImage
     *
     * @return Menu
     */
    public function setClassImage($classImage)
    {
        $this->classImage = $classImage;

        return $this;
    }

    /**
     * Get classImage
     *
     * @return string
     */
    public function getClassImage()
    {
        return $this->classImage;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Menu
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Menu
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set apparaitNav
     *
     * @param string $apparaitNav
     *
     * @return Menu
     */
    public function setApparaitNav($apparaitNav)
    {
        $this->apparaitNav = $apparaitNav;

        return $this;
    }

    /**
     * Get apparaitNav
     *
     * @return string
     */
    public function getApparaitNav()
    {
        return $this->apparaitNav;
    }

    /**
     * Set apparaitNavBar
     *
     * @param string $apparaitNavBar
     *
     * @return Menu
     */
    public function setApparaitNavBar($apparaitNavBar)
    {
        $this->apparaitNavBar = $apparaitNavBar;

        return $this;
    }

    /**
     * Get apparaitNavBar
     *
     * @return string
     */
    public function getApparaitNavBar()
    {
        return $this->apparaitNavBar;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Menu
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
     * @return Menu
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
     * Set cheminPere
     *
     * @param string $cheminPere
     *
     * @return Menu
     */
    public function setCheminPere($cheminPere)
    {
        $this->cheminPere = $cheminPere;

        return $this;
    }

    /**
     * Get cheminPere
     *
     * @return string
     */
    public function getCheminPere()
    {
        return $this->cheminPere;
    }

    /**
     * Set pere
     *
     * @param \Entity\Menu $pere
     *
     * @return Menu
     */
    public function setPere(\Entity\Menu $pere = null)
    {
        $this->pere = $pere;

        return $this;
    }

    /**
     * Get pere
     *
     * @return \Entity\Menu
     */
    public function getPere()
    {
        return $this->pere;
    }
}
