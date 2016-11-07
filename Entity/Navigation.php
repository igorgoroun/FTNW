<?php
/**
 * Created by PhpStorm.
 * User: snake
 * Date: 9/2/16
 * Time: 18:04
 */

namespace IgorGoroun\FTNWBundle\Entity;


class Navigation
{
    private $total = 0;
    private $current = 0;
    private $next = null;
    private $prev = null;
    private $new = null;
    private $reply = null;
    private $unseen = 0;

    /**
     * @return int
     */
    public function getUnseen()
    {
        return $this->unseen;
    }

    /**
     * @param int $unseen
     */
    public function setUnseen($unseen)
    {
        $this->unseen = $unseen;
    }

    private $list_unseen = null;
    private $list_all = null;

    private $new_to_sender;
    private $new_to_recipient;

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param int $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * @return null
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param null $next
     */
    public function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * @return null
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * @param null $prev
     */
    public function setPrev($prev)
    {
        $this->prev = $prev;
    }

    /**
     * @return null
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param null $new
     */
    public function setNew($new)
    {
        $this->new = $new;
    }

    /**
     * @return null
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @param null $reply
     */
    public function setReply($reply)
    {
        $this->reply = $reply;
    }

    /**
     * @return null
     */
    public function getListUnseen()
    {
        return $this->list_unseen;
    }

    /**
     * @param null $list_unseen
     */
    public function setListUnseen($list_unseen)
    {
        $this->list_unseen = $list_unseen;
    }

    /**
     * @return null
     */
    public function getListAll()
    {
        return $this->list_all;
    }

    /**
     * @param null $list_all
     */
    public function setListAll($list_all)
    {
        $this->list_all = $list_all;
    }

    /**
     * @return mixed
     */
    public function getNewToSender()
    {
        return $this->new_to_sender;
    }

    /**
     * @param mixed $new_to_sender
     */
    public function setNewToSender($new_to_sender)
    {
        $this->new_to_sender = $new_to_sender;
    }

    /**
     * @return mixed
     */
    public function getNewToRecipient()
    {
        return $this->new_to_recipient;
    }

    /**
     * @param mixed $new_to_recipient
     */
    public function setNewToRecipient($new_to_recipient)
    {
        $this->new_to_recipient = $new_to_recipient;
    }


}