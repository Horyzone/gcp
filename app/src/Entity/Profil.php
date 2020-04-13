<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\ProfilRepository")
 * @ORM\Table(name="profils")
 */
class Profil
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id_profil", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Permission", cascade={"persist"})
     * @ORM\JoinTable(name="profil_permission",
     * joinColumns={@ORM\JoinColumn(name="id_profil", referencedColumnName="id_profil")}, inverseJoinColumns={
     * @ORM\JoinColumn(name="id_permission", referencedColumnName="id_permission", unique=false)})
     */
    private $permissions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Rank
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add permission
     *
     * @param \App\Entity\Permission $permission
     */
    public function addPermission(\App\Entity\Permission $permission)
    {
        $this->permissions[] = $permission;
    }

    /**
     * Remove permission
     *
     * @param \App\Entity\Permission $permission
     */
    public function removePermission(\App\Entity\Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
