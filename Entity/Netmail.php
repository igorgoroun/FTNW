<?php

namespace IgorGoroun\FTNWBundle\Entity;

/**
 * Netmail
 */
class Netmail
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
    private $hFTNmid;

    /**
     * @var string
     */
    private $hFTNreply;

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
     * @var boolean
     */
    private $seen = false;

    /**
     * @var boolean
     */
    private $batched = false;

    /**
     * @var string
     */
    private $srcHeader;

    /**
     * @var \DateTime
     */
    private $cachedAt;

    /**
     * @var \IgorGoroun\FTNWBundle\Entity\Point
     */
    private $point;


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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * Set hFTNmid
     *
     * @param string $hFTNmid
     *
     * @return Netmail
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
     * @return Netmail
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

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * @return Netmail
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
     * Set seen
     *
     * @param boolean $seen
     *
     * @return Netmail
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
     * Set batched
     *
     * @param boolean $batched
     *
     * @return Netmail
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
     * Set srcHeader
     *
     * @param string $srcHeader
     *
     * @return Netmail
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
     * @return Netmail
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
     * Set point
     *
     * @param \IgorGoroun\FTNWBundle\Entity\Point $point
     *
     * @return Netmail
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
     * @ORM\PrePersist
     */
    public function setCachedDate()
    {
        $this->setCachedAt(new \DateTime());
    }

    public function makeRFCHeader() {
        $header = array();
        $header[] = "To: ".$this->hTo." <".$this->hToRfc.">";
        $header[] = "From: ".$this->hFrom." <".$this->hFromRfc.">";
        $header[] = "Date: ".$this->getHDate()->format('r');
        $header[] = "Subject: ".$this->subject;
        $header[] = "Message-ID: ".$this->messageId;
        $header[] = "X-FTN-MSGID: ".$this->hFromFtn." ".$this->getHFTNmid();
        return implode("\n",$header);
    }

}

