<?php

namespace IgorGoroun\FTNWBundle\Entity;

/**
 * Echoarea
 */
class Echoarea
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subscriptions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pointpackets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pointpackets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Echoarea
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
     * Add message
     *
     * @param \IgorGoroun\FTNWBundle\Entity\MessageCache $message
     *
     * @return Echoarea
     */
    public function addMessage(\IgorGoroun\FTNWBundle\Entity\MessageCache $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \IgorGoroun\FTNWBundle\Entity\MessageCache $message
     */
    public function removeMessage(\IgorGoroun\FTNWBundle\Entity\MessageCache $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add subscription
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Subscription $subscription
     *
     * @return Echoarea
     */
    public function addSubscription(\IgorGoroun\FTNWBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Subscription $subscription
     */
    public function removeSubscription(\IgorGoroun\FTNWBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Add pointpacket
     *
     * @param \IgorGoroun\FTNWBundle\Entity\PointMessage $pointpacket
     *
     * @return Echoarea
     */
    public function addPointpacket(\IgorGoroun\FTNWBundle\Entity\PointMessage $pointpacket)
    {
        $this->pointpackets[] = $pointpacket;

        return $this;
    }

    /**
     * Remove pointpacket
     *
     * @param \IgorGoroun\FTNWBundle\Entity\PointMessage $pointpacket
     */
    public function removePointpacket(\IgorGoroun\FTNWBundle\Entity\PointMessage $pointpacket)
    {
        $this->pointpackets->removeElement($pointpacket);
    }

    /**
     * Get pointpackets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPointpackets()
    {
        return $this->pointpackets;
    }
}
