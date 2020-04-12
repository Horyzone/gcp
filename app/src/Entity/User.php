<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id_user", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="last_name", type="string", length=30)
     */
    private $lastName;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=30)
     */
    private $firstName;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var integer $profil
     * @ORM\ManyToOne(targetEntity="App\Entity\Profil", cascade={"persist"})
     * @ORM\JoinColumn(name="id_profil", referencedColumnName="id_profil", nullable=true)
     */
    private $profil;

    /**
     * @var datetime $dateCreate
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @var datetime $dateHire
     *
     * @ORM\Column(name="date_hire", type="datetime", nullable=true)
     */
    private $dateHire;

    /**
     * @var datetime $dateLeaving
     *
     * @ORM\Column(name="date_leaving", type="datetime", nullable=true)
     */
    private $dateLeaving;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreate = new \DateTime();
        $this->password = null;
        $this->profil = null;
        $this->dateHire = null;
        $this->dateLeaving = null;
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
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
    * Get password
    *
    * @return string
    */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Password verification
     *
     * @param string $password
     *
     * @return bool
     */
    public function passwordVerify($password)
    {
        return password_verify($password, $this->password);
    }

    /**
    * Get profil
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set profil
     *
     * @param \App\Entity\Profil $profil
     *
     * @return User
     */
    public function setProfil(\App\Entity\Profil $profil = null)
    {
        $this->profil = $profil;
        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return User
     */
    public function setDateCreate(\DateTime $dateCreate)
    {
        $this->dateCreate = $dateCreate;
        return $this;
    }

    /**
     * Get dateHire
     *
     * @return \DateTime
     */
    public function getDateHire()
    {
        return $this->dateHire;
    }

    /**
     * Set dateHire
     *
     * @param \DateTime $dateHire
     *
     * @return User
     */
    public function setDateHire(\DateTime $dateHire = null)
    {
        $this->dateHire = $dateHire;
        return $this;
    }

    /**
     * Get dateLeaving
     *
     * @return \DateTime
     */
    public function getDateLeaving()
    {
        return $this->dateLeaving;
    }

    /**
     * Set dateLeaving
     *
     * @param \DateTime $dateLeaving
     *
     * @return User
     */
    public function setDateLeaving(\DateTime $dateLeaving = null)
    {
        $this->dateLeaving = $dateLeaving;
        return $this;
    }
}
