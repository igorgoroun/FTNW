<?php

namespace IgorGoroun\FTNWBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Point
 */
class Point implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $num;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $ifaddr;

    /**
     * @var string
     */
    private $ifname;

    /**
     * @var string
     */
    private $ftnaddr;

    /**
     * @var string
     */
    private $subscription;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var boolean
     */
    private $classic = false;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subscriptions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $echomail;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->echomail = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set num
     *
     * @param integer $num
     *
     * @return Point
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Point
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Point
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Set password
     *
     * @param string $password
     *
     * @return Point
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Set plainPassword
     *
     * @param string $plainPassword
     *
     * @return Point
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plainPassword
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set ifaddr
     *
     * @param string $ifaddr
     *
     * @return Point
     */
    public function setIfaddr($ifaddr)
    {
        $this->ifaddr = $ifaddr;

        return $this;
    }

    /**
     * Get ifaddr
     *
     * @return string
     */
    public function getIfaddr()
    {
        return $this->ifaddr;
    }

    /**
     * Set ifname
     *
     * @param string $ifname
     *
     * @return Point
     */
    public function setIfname($ifname)
    {
        $this->ifname = $ifname;

        return $this;
    }

    /**
     * Get ifname
     *
     * @return string
     */
    public function getIfname()
    {
        return $this->ifname;
    }

    /**
     * Set ftnaddr
     *
     * @param string $ftnaddr
     *
     * @return Point
     */
    public function setFtnaddr($ftnaddr)
    {
        $this->ftnaddr = $ftnaddr;

        return $this;
    }

    /**
     * Get ftnaddr
     *
     * @return string
     */
    public function getFtnaddr()
    {
        return $this->ftnaddr;
    }

    /**
     * Set subscription
     *
     * @param string $subscription
     *
     * @return Point
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return string
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return Point
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Point
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set classic
     *
     * @param boolean $classic
     *
     * @return Point
     */
    public function setClassic($classic)
    {
        $this->classic = $classic;

        return $this;
    }

    /**
     * Get classic
     *
     * @return boolean
     */
    public function getClassic()
    {
        return $this->classic;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Point
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
     * Add subscription
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Subscription $subscription
     *
     * @return Point
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
     * Add echomail
     *
     * @param \IgorGoroun\FTNWBundle\Entity\PointMessage $echomail
     *
     * @return Point
     */
    public function addEchomail(\IgorGoroun\FTNWBundle\Entity\PointMessage $echomail)
    {
        $this->echomail[] = $echomail;

        return $this;
    }

    /**
     * Remove echomail
     *
     * @param \IgorGoroun\FTNWBundle\Entity\PointMessage $echomail
     */
    public function removeEchomail(\IgorGoroun\FTNWBundle\Entity\PointMessage $echomail)
    {
        $this->echomail->removeElement($echomail);
    }

    /**
     * Get echomail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEchomail()
    {
        return $this->echomail;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        // Add your code here
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->num,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->num,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        if ($this->getActive())
            return array('ROLE_USER');
        else
            return array('ROLE_DENY');

    }
    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        // The bcrypt algorithm don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $netmail;


    /**
     * Add netmail
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Netmail $netmail
     *
     * @return Point
     */
    public function addNetmail(\IgorGoroun\FTNWBundle\Entity\Netmail $netmail)
    {
        $this->netmail[] = $netmail;

        return $this;
    }

    /**
     * Remove netmail
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Netmail $netmail
     */
    public function removeNetmail(\IgorGoroun\FTNWBundle\Entity\Netmail $netmail)
    {
        $this->netmail->removeElement($netmail);
    }

    /**
     * Get netmail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNetmail()
    {
        return $this->netmail;
    }
    /**
     * @var boolean
     */
    private $aslist_netmail = false;

    /**
     * @var boolean
     */
    private $aslist_echomail = false;


    /**
     * Set aslistNetmail
     *
     * @param boolean $aslistNetmail
     *
     * @return Point
     */
    public function setAslistNetmail($aslistNetmail)
    {
        $this->aslist_netmail = $aslistNetmail;

        return $this;
    }

    /**
     * Get aslistNetmail
     *
     * @return boolean
     */
    public function getAslistNetmail()
    {
        return $this->aslist_netmail;
    }

    /**
     * Set aslistEchomail
     *
     * @param boolean $aslistEchomail
     *
     * @return Point
     */
    public function setAslistEchomail($aslistEchomail)
    {
        $this->aslist_echomail = $aslistEchomail;

        return $this;
    }

    /**
     * Get aslistEchomail
     *
     * @return boolean
     */
    public function getAslistEchomail()
    {
        return $this->aslist_echomail;
    }
}
