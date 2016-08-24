<?php

namespace IgorGoroun\FTNWBundle\Entity;

/**
 * PointMessage
 */
class PointMessage
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
     * @var boolean
     */
    private $seen;

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
     * @return PointMessage
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
     * Set seen
     *
     * @param boolean $seen
     *
     * @return PointMessage
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set point
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Point $point
     *
     * @return PointMessage
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
     * @return PointMessage
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
     * @var \IgorGoroun\FTNWBundle\Entity\MessageCache
     */
    private $message;


    /**
     * Set message
     *
     * @param \IgorGoroun\FTNWBundle\Entity\MessageCache $message
     *
     * @return PointMessage
     */
    public function setMessage(\IgorGoroun\FTNWBundle\Entity\MessageCache $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \IgorGoroun\FTNWBundle\Entity\MessageCache
     */
    public function getMessage()
    {
        return $this->message;
    }
}
