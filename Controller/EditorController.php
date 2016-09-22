<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Doctrine\ORM\NoResultException;
use IgorGoroun\FTNWBundle\Entity\MessageBatch;
use IgorGoroun\FTNWBundle\Entity\MessageCache;
use IgorGoroun\FTNWBundle\Entity\Echoarea;
use IgorGoroun\FTNWBundle\Entity\Navigation;
use IgorGoroun\FTNWBundle\Entity\Netmail;
use IgorGoroun\FTNWBundle\Form\MessagePostType;
use IgorGoroun\FTNWBundle\Form\MessageReplyType;
use IgorGoroun\FTNWBundle\Form\NetmailPostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EditorController extends Controller
{
    public function netmailListAction(Request $request) {

    }

    public function echomailListOpsAction($group_id, Request $request) {
        if ($request->request->get('type')=='markread' && count($request->request->get('unreads'))>0) {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $qb->update("FTNWBundle:PointMessage","p")->set('p.seen',true)->where('p.id IN (:ids)')
                ->setParameter('ids',array_keys($request->request->get('unreads')));
            if ($qb->getQuery()->execute()) {
                $this->addFlash('notice','Selected messages marked as read');
            } else {
                $this->addFlash('error','Cannot mark selected messages as read');
            }

        } elseif ($request->request->get('type')=='delete' && count($request->request->get('unreads'))>0) {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $qb->delete()->from("FTNWBundle:PointMessage",'p')
                ->where('p.id IN (:ids)')
                ->setParameter('ids',array_keys($request->request->get('unreads')));
            if ($qb->getQuery()->execute()) {
                $this->addFlash('notice','Selected messages deleted');
            } else {
                $this->addFlash('error','Cannot delete selected messages');
            }

        }
        return $this->redirectToRoute('fidonews_group_list',['group_id'=>$group_id]);
    }
    public function echomailListAction($group_id, Request $request) {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select("m.id,m.hFrom,m.hFromFtn,m.hTo,m.subject,m.hDate,p.id as pid")
            ->from("FTNWBundle:PointMessage","p")
            ->leftJoin("FTNWBundle:MessageCache","m",'WITH',"m.id=p.message")
            ->where("p.point = :point_id")
            ->andWhere("p.area = :area_id")
            ->andWhere("p.seen = 0")
            ->orderBy("m.hDate","asc")
            ->setParameter("point_id",$this->getUser()->getId())
            ->setParameter("area_id",$group_id);
        $pm = $qb->getQuery()->getResult();

        return $this->render("FTNWBundle:Editor:echomail-group-list.html.twig", array(
            'groups'=> $this->getPointGroups(),
            'group_current' => $this->getDoctrine()->getRepository("FTNWBundle:Echoarea")->find($group_id),
            'netmail_unread' => $this->getPointUnreadNetmail(),
            'messages' => $pm,
        ));
    }
    public function netmailReplyAction(Netmail $message,Request $request) {
        if (!$message) {
            $this->addFlash("error","Invalid message ID");
            return $this->redirectToRoute("netmail");
        }

        $point = $this->getUser();

        $batch = new Netmail();
        $batch->setHTo($message->getHFrom());
        $batch->setHToFtn($message->getHFromFtn());
        $batch->setSubject($message->getSubject());
        $batch->setBody("Hi ".$batch->getHTo()."!\n\n".$this->bodyReplier($message->getBody(),$message->getHFrom())."\n\n".$point->getSubscription()."\n");

        $form = $this->createForm(NetmailPostType::class,$batch);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Add data to batch record
            $batch->setPoint($point);
            $batch->setHFrom($point->getUsername());
            $batch->setHFromFtn($point->getFtnaddr());
            $batch->setHFromRfc($this->makeRFC($batch->getHFrom(),$batch->getHFromFtn()));
            $batch->setHTo($message->getHFrom());
            $batch->setHToFtn($message->getHFromFtn());
            $batch->setHToRfc($this->makeRFC($batch->getHTo(),$batch->getHToFtn()));
            $batch->setHFTNmid(substr(md5(time()),0,8));
            $batch->setHFTNreply($message->getHFTNmid());
            $batch->setMessageId("<".time()."@".$point->getIfaddr().">");
            $batch->setHDate(new \DateTime());
            $batch->setOrigin($point->getOrigin());
            $batch->setTearline($this->getParameter('node_tearline'));
            $batch->setPid($this->getParameter('ftnw_ver'));

            // Validate and save data
            if ($form->isValid()) {
                $batch->setSrcHeader($batch->makeRFCHeader());
                $em = $this->getDoctrine()->getManager();
                $em->persist($batch);
                $em->flush();
                $this->addFlash("notice", "Netmail sent to ".$batch->getHTo());
                return $this->redirectToRoute("netmail_message", ['netmail_id' => $batch->getId()]);
            }
        }

        return $this->render('FTNWBundle:Editor:netmail-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'groups' => $this->getPointGroups(),
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    public function netmailPostAction(Request $request) {
        $point = $this->getUser();

        $batch = new Netmail();
        $batch->setBody("\n\n\n".$point->getSubscription()."\n");

        $form = $this->createForm(NetmailPostType::class,$batch);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Add data to batch record
            $batch->setPoint($point);
            $batch->setHFrom($point->getUsername());
            $batch->setHFromFtn($point->getFtnaddr());
            $batch->setHFromRfc($this->makeRFC($batch->getHFrom(),$batch->getHFromFtn()));
            $batch->setOrigin($point->getOrigin());
            $batch->setHFTNmid(substr(md5(time()),0,8));
            $batch->setMessageId("<".time()."@".$point->getIfaddr().">");
            $batch->setHDate(new \DateTime());
            $batch->setTearline($this->getParameter('node_tearline'));
            $batch->setPid($this->getParameter('ftnw_ver'));

            // Validate and save data
            if ($form->isValid() && $batch->getHToFtn()!=null) {
                $batch->setHToRfc($this->makeRFC($batch->getHTo(),$batch->getHToFtn()));
                $batch->setSrcHeader($batch->makeRFCHeader());
                $em = $this->getDoctrine()->getManager();
                $em->persist($batch);
                $em->flush();
                $this->addFlash("notice", "Netmail sent to ".$batch->getHTo());
                return $this->redirectToRoute("netmail_message", ['netmail_id' => $batch->getId()]);
            }
        }

        return $this->render('FTNWBundle:Editor:netmail-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'groups' => $this->getPointGroups(),
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }
    public function netmailcheckAction() {
        // Find first UNSEEN message
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select("n.id")
            ->from("FTNWBundle:Netmail","n")
            ->where("n.point = :point_id")
            ->andWhere("n.seen = 0")
            ->orderBy("n.hDate","asc")
            ->setParameter("point_id",$this->getUser()->getId())
            ->setMaxResults(1);
        try {
            $id = $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            $id = false;
        }

        // If all messages are seen - find last message ID
        if (!$id || !is_numeric($id)) {
            $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
            $qb->select("n.id")
                ->from("FTNWBundle:Netmail","n")
                ->where("n.point = :point_id")
                ->addOrderBy("n.hDate","desc")
                ->addOrderBy("n.id","DESC")
                ->setParameter("point_id",$this->getUser()->getId())
                ->setMaxResults(1);
            try {
                $id = $qb->getQuery()->getSingleScalarResult();
            } catch (NoResultException $e) {
                $id = false;
            }
        }
        // render template

        if ($id) {
            return $this->redirectToRoute('netmail_message', ['netmail_id' => $id]);
        } else {
            return $this->render("FTNWBundle:Editor:netmail.html.twig", array(
                'groups'=> $this->getPointGroups(),
                'message' => false,
                'nav' => false,
                'netmail_unread' => $this->getPointUnreadNetmail(),
            ));
        }
    }

    public function netmailAction ($netmail_id) {
        // get message
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select(['m','n.id as next_id','p.id as prev_id'])
            ->from('FTNWBundle:Netmail','m')
            ->leftJoin('FTNWBundle:Netmail','n','WITH','n.hDate>m.hDate and n.point=:pnt_id')
            ->leftJoin('FTNWBundle:Netmail','p','WITH','p.hDate<m.hDate and p.point=:pnt_id')
            ->where('m.id=:nm_id')
            ->andWhere('m.point=:pnt_id')
            ->addOrderBy('m.hDate','desc')
            ->addOrderBy('n.hDate','asc')
            ->addOrderBy('p.hDate','desc')
            ->setMaxResults(1)
            ->setParameter('nm_id',$netmail_id)
            ->setParameter('pnt_id',$this->getUser()->getId());
        $netmail = $qb->getQuery()->getScalarResult();

        // check netmail exists for point
        if (!$netmail) {
            $this->addFlash('error',"Requested message not found");
            return $this->redirectToRoute("fidonews_editor");
        } else $netmail = $netmail[0];

//        dump($netmail);

        // create navigation
        $navigation = new Navigation();
        $navigation->setNext($netmail['next_id']);
        $navigation->setPrev($netmail['prev_id']);

        $navigation->setNew($this->generateUrl('netmail_post'));
        $navigation->setReply($this->generateUrl('netmail_reply',['id'=>$netmail['m_id']]));


        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("n.id")
            ->from("FTNWBundle:Netmail","n")
            ->where("n.point = :point_id")
            ->orderBy("n.hDate","asc")
            ->setParameter("point_id",$this->getUser()->getId());
        $am = $qb->getQuery()->getArrayResult();

        $navigation->setTotal(count($am));

        for($i=0;$i<count($am);$i++) {
            if ($am[$i]['id'] == $netmail['m_id']) {
                $navigation->setCurrent($i+1);
            }
        }

//        dump($navigation);

        // set message as read
        $nm = $em->find("FTNWBundle:Netmail",$netmail['m_id'])->setSeen(true);
        $em->persist($nm);
        $em->flush();

        // Colorize quotes in message body
        $netmail['m_body'] = $this->colorizeQuotes($netmail['m_body']);

        // return render
        return $this->render("FTNWBundle:Editor:netmail.html.twig", array(
            'groups'=> $this->getPointGroups(),
            'message' => $netmail,
            'nav' => $navigation,
            'netmail_unread' => $this->getPointUnreadNetmail(),
            ''
        ));
    }

    public function postAction(Echoarea $area, Request $request) {
        //dump($area);
        $point = $this->getUser();

        $batch = new MessageBatch();
        $batch->setHTo('All');
        /* TODO: Заменить шабон
        */
        $batch->setBody("Hi ".$batch->getHTo()."!\n\n\n".$point->getSubscription()."\n");

        $form = $this->createForm(MessagePostType::class,$batch);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Add data to batch record
            $batch->setArea($area->getName());
            $batch->setHFrom($point->getUsername());
            $batch->setHFromFtn($point->getFtnaddr());
            $batch->setHFromRfc($point->getIfaddr());
            $batch->setOrigin($point->getOrigin());
            $batch->setHFTNmid(substr(md5(time()),0,8));
            $batch->setMessageId("<".time()."@".$point->getIfaddr().">");
            $batch->setHDate(new \DateTime());
            $batch->setTearline($this->getParameter('node_tearline'));
            $batch->setPid($this->getParameter('ftnw_ver'));

            // Validate and save data
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($batch);
                $em->flush();
                $this->addFlash("notice", "Posted to area ".$area->getName());
                return $this->redirectToRoute("fidonews_group", ['group_id' => $area->getId()]);
            }
        }

        $groups = $this->getPointGroups();
        return $this->render('FTNWBundle:Editor:echomail-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'group_current' => $area,
            'groups' => $groups,
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    public function replyAction(MessageCache $message, Request $request) {
        if (!$message) {
            $this->addFlash("error","Invalid message ID");
            return $this->redirectToRoute("fidonews_editor");
        }

        $point = $this->getUser();

        $batch = new MessageBatch();
        $batch->setHTo($message->getHFrom());
        $batch->setHToFtn($message->getHFromFtn());
        $batch->setSubject($message->getSubject());
        $batch->setBody("Hi ".$batch->getHTo()."!\n\n".$this->bodyReplier($message->getBody(),$message->getHFrom())."\n\n".$point->getSubscription()."\n");

        $form = $this->createForm(MessageReplyType::class,$batch);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Add data to batch record
            $batch->setArea($message->getEchoarea()->getName());
            $batch->setHFrom($point->getUsername());
            $batch->setHFromFtn($point->getFtnaddr());
            $batch->setHFromRfc($point->getIfaddr());
            $batch->setHTo($message->getHFrom());
            $batch->setHToFtn($message->getHFromFtn());
            $batch->setHToRfc($message->getHFromRfc());
            $batch->setHFTNmid(substr(md5(time()),0,8));
            $batch->setHFTNreply($message->getHFTNmid());
            $batch->setMessageId("<".time()."@".$point->getIfaddr().">");
            $batch->setReplyId($message->getMessageId());
            $batch->setHDate(new \DateTime());
            $batch->setOrigin($point->getOrigin());
            $batch->setTearline($this->getParameter('node_tearline'));
            $batch->setPid($this->getParameter('ftnw_ver'));

            // Validate and save data
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($batch);
                $em->flush();
                $this->addFlash("notice", "Posted to area ".$message->getEchoarea()->getName());
                return $this->redirectToRoute("fidonews_group", ['group_id' => $message->getEchoarea()->getId()]);
            }
        }
        $groups = $this->getPointGroups();

        return $this->render('FTNWBundle:Editor:echomail-reply.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'group_current' => $message->getEchoarea(),
            'groups' => $groups,
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    private function bodyReplier($body,$from) {
        // parse from name
        $nm = explode(" ",$from,2);
        $new_quote = " ";
        foreach ($nm as $name) {
            $new_quote .= substr($name,0,1);
        }
        $new_quote .= "> ";

        $barr = explode("\n",$body);
        $replied = array();
        foreach ($barr as $bline) {
            if ($bline!="") {
                if (preg_match("/([a-zA-Z\ ]+\>{1,})\ (.+)/",$bline,$quoted)) {
                    $replied []= $quoted[1]."> ".$quoted[2];
                } else {
                    $replied []= $new_quote.trim($bline);
                }

            }
        }

        return implode("\n",$replied);
    }

    public function groupsAction()
    {
        // Get grouplist
        $groups = $this->getPointGroups();
        // render template
        return $this->render('FTNWBundle:Editor:groups.html.twig', array(
            'groups'=>$groups,
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    public function groupAction($group_id) {
        // Find first UNSEEN message
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select("m.id")
            ->from("FTNWBundle:PointMessage","p")
            ->leftJoin("FTNWBundle:MessageCache","m",'WITH',"m.id=p.message")
            ->where("p.point = :point_id")
            ->andWhere("p.area = :area_id")
            ->andWhere("p.seen = 0")
            ->orderBy("m.hDate","asc")
            ->setParameter("point_id",$this->getUser()->getId())
            ->setParameter("area_id",$group_id)
            ->setMaxResults(1);
        try {
            $id = $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            $id = false;
        }

        // If all messages are seen - find last message ID
        if (!$id || !is_numeric($id)) {
            $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
            $qb->select("m.id")
                ->from("FTNWBundle:PointMessage","p")
                ->leftJoin("FTNWBundle:MessageCache","m",'WITH',"m.id=p.message")
                ->where("p.point = :point_id")
                ->andWhere("p.area = :area_id")
                ->addOrderBy("m.hDate","desc")
                ->addOrderBy("m.id","DESC")
                ->setParameter("point_id",$this->getUser()->getId())
                ->setParameter("area_id",$group_id)
                ->setMaxResults(1);
            try {
                $id = $qb->getQuery()->getSingleScalarResult();
            } catch (NoResultException $e) {
                //$this->addFlash("notice","No messages in selected area");
                //return $this->redirectToRoute("fidonews_editor");
                $id = false;
            }
        }
        if ($id) {
            return $this->redirectToRoute('fidonews_message', ['group_id' => $group_id, 'message_number' => $id]);
        } else {
            return $this->render("FTNWBundle:Editor:groups.html.twig", array(
                'groups'=> $this->getPointGroups(),
                'group_current' => $this->getDoctrine()->getRepository("FTNWBundle:Echoarea")->find($group_id),
                'message' => false,
                'nav' => false,
                'netmail_unread' => $this->getPointUnreadNetmail(),
            ));

        }
    }

    public function messageAction($group_id,$message_number=0)
    {
        $em = $this->getDoctrine();

        $message = $em->getRepository("FTNWBundle:MessageCache")->find($message_number);

        $qb = $em->getManager()->createQueryBuilder();
        $qb->select("m.id")
            ->from("FTNWBundle:PointMessage","p")
            ->leftJoin("FTNWBundle:MessageCache","m",'WITH',"m.id=p.message")
            ->where("p.point = :point_id")
            ->andWhere("p.area = :area_id")
            ->orderBy("m.hDate","asc")
            ->setParameter("point_id",$this->getUser()->getId())
            ->setParameter("area_id",$group_id);
        $am = $qb->getQuery()->getArrayResult();

        $nav = array();
        $nav["total"] = count($am);
        for($i=0;$i<count($am);$i++) {
            if ($am[$i]['id'] == $message->getId()) {
                if (array_key_exists($i-1,$am)) {
                    $nav['prev'] = $am[$i-1]['id'];
                }
                if (array_key_exists($i+1,$am)) {
                    $nav['next'] = $am[$i+1]['id'];
                }
                $nav["current"] = $i+1;
            }
        }

        // set message as read
        $pm = $em->getRepository("FTNWBundle:PointMessage")->findOneBy(["point"=>$this->getUser()->getId(),"message"=>$message->getId(),"area"=>$message->getEchoarea()->getId()]);
        $pm->setSeen(true);
        $r = $em->getManager();
        $r->persist($pm);
        $r->flush();

        // groups list
        $groups = $this->getPointGroups();

        // Colorize quotes in message body
        $message->setBody($this->colorizeQuotes($message->getBody()));


        /*$attachment = false;
        if (preg_match("/begin \d{3} (.+)([\w\W]+)end/",$message->getBody(),$data)) {
            $attachment = array('file'=>$data[1],'content'=>convert_uudecode($data[2]));
        }*/
        if (preg_match("/begin \d{3} (.+)([\w\W]+)end/",$message->getBody(),$data)) {
            $message->setBody(str_replace($data[2],"\n-UUE/cutted-\n",$message->getBody()));
        }

        // return render
        return $this->render("FTNWBundle:Editor:groups.html.twig", array(
            'groups'=> $groups,
            'group_current' => $message->getEchoarea(),
            'message' => $message,
            'nav' => $nav,
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    private function getPointGroups() {
        $point_id = $this->getUser()->getId();

        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select(["e.name","e.id","COUNT(m.id) as cnt"])
            ->from("FTNWBundle:Subscription",'s')
            ->leftJoin("FTNWBundle:Echoarea",'e','WITH','e.id=s.area')
            ->leftJoin("FTNWBundle:PointMessage","m",'WITH',"m.area=s.area and m.point=:point_id and m.seen=0")
            ->where("s.point = :point_id")
            ->groupBy("e.name")
            ->addOrderBy("cnt","DESC")
            ->addOrderBy('e.name','ASC')
            ->setParameter("point_id",$point_id);
        $grouplist = $qb->getQuery()->getResult();
        return $grouplist;
    }

    private function getPointUnreadNetmail() {
        $em = $this->getDoctrine()->getRepository("FTNWBundle:Netmail");
        $cnt = $em->findBy(['point'=>$this->getUser(),'seen'=>0]);
        return count($cnt);
    }

    private function colorizeQuotes ($body) {
        $mlines = explode("\n",$body);
        $ml_repl = false;
        for ($l=0;$l<count($mlines);$l++) {
            if (preg_match("/([a-zA-ZА-Яа-я\ ]+\>{1,})\ /",$mlines[$l])) {
                $mlines[$l] = "<span>".$mlines[$l]."</span>";
                $ml_repl = true;
            }
        }
        if ($ml_repl) return implode("\n",$mlines);
        else return $body;
    }

    public function makeRFC($name=false,$ftn) {
        if (preg_match("/^(\d{1})\:(\d{1,4})\/(\d{1,4})\.(\d{1,4})/",$ftn,$data)) {
            $addr = "p".$data[4].".f".$data[3].".n".$data[2].".z".$data[1].".fidonet.org";
        } elseif (preg_match("/^(\d{1})\:(\d{1,4})\/(\d{1,4})/",$ftn,$data)) {
            $addr = "f".$data[3].".n".$data[2].".z".$data[1].".fidonet.org";
        } else return false;
        if ($name) {
            $rfc_name = str_replace(['_',' ',"'","\""],['.','.','',''],$name);
            return $rfc_name."@".$addr;
        } else {
            return $addr;
        }
    }
    public function makeFTN($rfc) {
        if (strpos($rfc,'@') === false) {
            $ad = $rfc;
        } else {
            list($fr,$ad) = explode("@",$rfc);
        }
        if (preg_match("/^p(\d{1,4})\.f(\d{1,4})\.n(\d{1,4})\.z(\d{1})\.(.+)/",$ad,$data)) {
            return $data[4].":".$data[3]."/".$data[2].".".$data[1];
        } elseif (preg_match("/^f(\d{1,4})\.n(\d{1,4})\.z(\d{1})\.(.+)/",$ad,$data)) {
            return $data[4].":".$data[3]."/".$data[2];
        } else {
            return false;
        }
    }


}
