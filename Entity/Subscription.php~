<?php

namespace IgorGoroun\FTNWBundle\Entity;

/**
 * Subscription
 */
class Subscription
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \IgorGoroun\FTNWBundle\Entity\Point
     */
    private $point;

    /**
     * @var \IgorGoroun\FTNWBundle\Entity\Echoarea
     */
    private $area;


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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Subscription
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set point
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Point $point
     *
     * @return Subscription
     */
    public function setPoint(\IgorGoroun\FTNWBundle\Entity\Point $point = null)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return \IgorGoroun\FTNWBundle\Entity\Point
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set area
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Echoarea $area
     *
     * @return Subscription
     */
    public function setArea(\IgorGoroun\FTNWBundle\Entity\Echoarea $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \IgorGoroun\FTNWBundle\Entity\Echoarea
     */
    public function getArea()
    {
        return $this->area;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        // Add your code here
    }
}
