<?php

namespace IgorGoroun\FTNWBundle\Entity;

/**
 * MessageCache
 */
class MessageCache
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string
     */
    private $hFrom;

    /**
     * @var string
     */
    private $hFromRfc;

    /**
     * @var string
     */
    private $hFromFtn;

    /**
     * @var string
     */
    private $hTo;

    /**
     * @var string
     */
    private $hToFtn;

    /**
     * @var string
     */
    private $hToRfc;

    /**
     * @var \DateTime
     */
    private $hDate;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $tearline;

    /**
     * @var string
     */
    private $tid;

    /**
     * @var string
     */
    private $pid;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $srcHeader;

    /**
     * @var \DateTime
     */
    private $cachedAt;

    /**
     * @var boolean
     */
    private $batched = false;

    /**
     * @var \IgorGoroun\FTNWBundle\Entity\Echoarea
     */
    private $echoarea;


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
     * Set messageId
     *
     * @param string $messageId
     *
     * @return MessageCache
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set hFrom
     *
     * @param string $hFrom
     *
     * @return MessageCache
     */
    public function setHFrom($hFrom)
    {
        $this->hFrom = $hFrom;

        return $this;
    }

    /**
     * Get hFrom
     *
     * @return string
     */
    public function getHFrom()
    {
        return $this->hFrom;
    }

    /**
     * Set hFromRfc
     *
     * @param string $hFromRfc
     *
     * @return MessageCache
     */
    public function setHFromRfc($hFromRfc)
    {
        $this->hFromRfc = $hFromRfc;

        return $this;
    }

    /**
     * Get hFromRfc
     *
     * @return string
     */
    public function getHFromRfc()
    {
        return $this->hFromRfc;
    }

    /**
     * Set hFromFtn
     *
     * @param string $hFromFtn
     *
     * @return MessageCache
     */
    public function setHFromFtn($hFromFtn)
    {
        $this->hFromFtn = $hFromFtn;

        return $this;
    }

    /**
     * Get hFromFtn
     *
     * @return string
     */
    public function getHFromFtn()
    {
        return $this->hFromFtn;
    }

    /**
     * Set hTo
     *
     * @param string $hTo
     *
     * @return MessageCache
     */
    public function setHTo($hTo)
    {
        $this->hTo = $hTo;

        return $this;
    }

    /**
     * Get hTo
     *
     * @return string
     */
    public function getHTo()
    {
        return $this->hTo;
    }

    /**
     * Set hToFtn
     *
     * @param string $hToFtn
     *
     * @return MessageCache
     */
    public function setHToFtn($hToFtn)
    {
        $this->hToFtn = $hToFtn;

        return $this;
    }

    /**
     * Get hToFtn
     *
     * @return string
     */
    public function getHToFtn()
    {
        return $this->hToFtn;
    }

    /**
     * Set hToRfc
     *
     * @param string $hToRfc
     *
     * @return MessageCache
     */
    public function setHToRfc($hToRfc)
    {
        $this->hToRfc = $hToRfc;

        return $this;
    }

    /**
     * Get hToRfc
     *
     * @return string
     */
    public function getHToRfc()
    {
        return $this->hToRfc;
    }

    /**
     * Set hDate
     *
     * @param \DateTime $hDate
     *
     * @return MessageCache
     */
    public function setHDate($hDate)
    {
        $this->hDate = $hDate;

        return $this;
    }

    /**
     * Get hDate
     *
     * @return \DateTime
     */
    public function getHDate()
    {
        return $this->hDate;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return MessageCache
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return MessageCache
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set tearline
     *
     * @param string $tearline
     *
     * @return MessageCache
     */
    public function setTearline($tearline)
    {
        $this->tearline = $tearline;

        return $this;
    }

    /**
     * Get tearline
     *
     * @return string
     */
    public function getTearline()
    {
        return $this->tearline;
    }

    /**
     * Set tid
     *
     * @param string $tid
     *
     * @return MessageCache
     */
    public function setTid($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return string
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set pid
     *
     * @param string $pid
     *
     * @return MessageCache
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return string
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return MessageCache
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
     * Set body
     *
     * @param string $body
     *
     * @return MessageCache
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set srcHeader
     *
     * @param string $srcHeader
     *
     * @return MessageCache
     */
    public function setSrcHeader($srcHeader)
    {
        $this->srcHeader = $srcHeader;

        return $this;
    }

    /**
     * Get srcHeader
     *
     * @return string
     */
    public function getSrcHeader()
    {
        return $this->srcHeader;
    }

    /**
     * Set cachedAt
     *
     * @param \DateTime $cachedAt
     *
     * @return MessageCache
     */
    public function setCachedAt($cachedAt)
    {
        $this->cachedAt = $cachedAt;

        return $this;
    }

    /**
     * Get cachedAt
     *
     * @return \DateTime
     */
    public function getCachedAt()
    {
        return $this->cachedAt;
    }

    /**
     * Set batched
     *
     * @param boolean $batched
     *
     * @return MessageCache
     */
    public function setBatched($batched)
    {
        $this->batched = $batched;

        return $this;
    }

    /**
     * Get batched
     *
     * @return boolean
     */
    public function getBatched()
    {
        return $this->batched;
    }

    /**
     * Set echoarea
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Echoarea $echoarea
     *
     * @return MessageCache
     */
    public function setEchoarea(\IgorGoroun\FTNWBundle\Entity\Echoarea $echoarea = null)
    {
        $this->echoarea = $echoarea;

        return $this;
    }

    /**
     * Get echoarea
     *
     * @return \IgorGoroun\FTNWBundle\Entity\Echoarea
     */
    public function getEchoarea()
    {
        return $this->echoarea;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCachedDate()
    {
        // Add your code here
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pointmessages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pointmessages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pointmessage
     *
     * @param \IgorGoroun\FTNWBundle\Entity\PointMessage $pointmessage
     *
     * @return MessageCache
     */
    public function addPointmessage(\IgorGoroun\FTNWBundle\Entity\PointMessage $pointmessage)
    {
        $this->pointmessages[] = $pointmessage;

        return $this;
    }

    /**
     * Remove pointmessage
     *
     * @param \IgorGoroun\FTNWBundle\Entity\PointMessage $pointmessage
     */
    public function removePointmessage(\IgorGoroun\FTNWBundle\Entity\PointMessage $pointmessage)
    {
        $this->pointmessages->removeElement($pointmessage);
    }

    /**
     * Get pointmessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPointmessages()
    {
        return $this->pointmessages;
    }
    /**
     * @var string
     */
    private $hFTNmid;

    /**
     * @var string
     */
    private $hFTNreply;


    /**
     * Set hFTNmid
     *
     * @param string $hFTNmid
     *
     * @return MessageCache
     */
    public function setHFTNmid($hFTNmid)
    {
        $this->hFTNmid = $hFTNmid;

        return $this;
    }

    /**
     * Get hFTNmid
     *
     * @return string
     */
    public function getHFTNmid()
    {
        return $this->hFTNmid;
    }

    /**
     * Set hFTNreply
     *
     * @param string $hFTNreply
     *
     * @return MessageCache
     */
    public function setHFTNreply($hFTNreply)
    {
        $this->hFTNreply = $hFTNreply;

        return $this;
    }

    /**
     * Get hFTNreply
     *
     * @return string
     */
    public function getHFTNreply()
    {
        return $this->hFTNreply;
    }
}
