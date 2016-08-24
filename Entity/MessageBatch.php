<?php

namespace IgorGoroun\FTNWBundle\Entity;

/**
 * MessageBatch
 */
class MessageBatch
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $area;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string
     */
    private $replyId;

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
    private $batched = false;


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
     * Set area
     *
     * @param string $area
     *
     * @return MessageBatch
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set messageId
     *
     * @param string $messageId
     *
     * @return MessageBatch
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
     * Set replyId
     *
     * @param string $replyId
     *
     * @return MessageBatch
     */
    public function setReplyId($replyId)
    {
        $this->replyId = $replyId;

        return $this;
    }

    /**
     * Get replyId
     *
     * @return string
     */
    public function getReplyId()
    {
        return $this->replyId;
    }

    /**
     * Set hFrom
     *
     * @param string $hFrom
     *
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * @return MessageBatch
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
     * Set batched
     *
     * @param boolean $batched
     *
     * @return MessageBatch
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
}
