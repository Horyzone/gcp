<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\PeriodeRepository")
 * @ORM\Table(name="periodes")
 */
class Periode
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_periode", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime $dateStart
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     */
    private $dateStart;

    /**
     * @var datetime $dateEnd
     *
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateStart = null;
        $this->dateEnd = null;
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
    * Get dateStart
    *
    * @return \DateTime
    */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Periode
     */
    public function setDateStart(\DateTime $dateStart = null)
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
    * Get dateEnd
    *
    * @return \DateTime
    */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Periode
     */
    public function setDateEnd(\DateTime $dateEnd = null)
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }
}
