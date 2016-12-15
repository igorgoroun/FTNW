<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\ORM\NoResultException;
use IgorGoroun\FTNWBundle\Entity\MessageBatch;
use IgorGoroun\FTNWBundle\Entity\MessageCache;
use IgorGoroun\FTNWBundle\Entity\Echoarea;
use IgorGoroun\FTNWBundle\Entity\Navigation;
use IgorGoroun\FTNWBundle\Entity\Netmail;
use IgorGoroun\FTNWBundle\Form\MessagePostType;
use IgorGoroun\FTNWBundle\Form\MessageReplyType;
use IgorGoroun\FTNWBundle\Form\NetmailPostType;
use IgorGoroun\FTNWBundle\Form\EmailPostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EditorController extends Controller
{
    public function netmailListOpsAction(Request $request) {
        if ($request->request->get('type')=='markread' && count($request->request->get('unreads'))>0) {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $qb->update("FTNWBundle:Netmail","p")->set('p.seen',true)
                ->where('p.id IN (:ids)')
                ->andWhere('p.point = :point_id')
                ->setParameter('point_id',$this->getUser()->getId())
                ->setParameter('ids',array_keys($request->request->get('unreads')));
            if ($qb->getQuery()->execute()) {
                $this->addFlash('notice','Selected messages marked as read');
            } else {
                $this->addFlash('error','Cannot mark selected messages as read');
            }

        } elseif ($request->request->get('type')=='delete' && count($request->request->get('unreads'))>0) {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $qb->delete()->from("FTNWBundle:Netmail",'p')
                ->where('p.id IN (:ids)')
                ->andWhere('p.point = :point_id')
                ->setParameter('point_id',$this->getUser()->getId())
                ->setParameter('ids',array_keys($request->request->get('unreads')));
            if ($qb->getQuery()->execute()) {
                $this->addFlash('notice','Selected messages deleted');
            } else {
                $this->addFlash('error','Cannot delete selected messages');
            }

        }
        return $this->redirectToRoute('netmail');
    }
    public function netmailListAction($all=false, Request $request) {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        // Count total records for pagination
        $qb->select($qb->expr()->count('nm'))
            ->from('FTNWBundle:Netmail', 'nm')
            ->where('nm.point = :point_id')
            ->setParameter('point_id',$this->getUser()->getId());
        if (!$all) {
            $qb->andWhere("nm.seen = 0");
        }

        $totalRecords = $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 1800)
            ->getSingleScalarResult();

        // Init paginator
        $paginator = $this->paginate(
            $totalRecords,
            $pageNumber = $request->get('page'),
            $onPage=100,
            'list'
        );

        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select("m.id,m.hFrom,m.hFromFtn,m.hTo,m.hToFtn,m.subject,m.hDate,m.seen")
            ->from("FTNWBundle:Netmail","m")
            ->where("m.point = :point_id")
            ->orderBy("m.hDate","desc")
            ->setMaxResults($paginator->limit)
            ->setFirstResult($paginator->offset)
            ->setParameter("point_id",$this->getUser()->getId());

        if (!$all) {
            $qb->andWhere("m.seen = 0");
        }

        $pm = $qb->getQuery()->getResult();

        if (!$all && count($pm)==0) {
            $this->addFlash('notice','You have no unseen messages');
            return $this->redirectToRoute('netmail');
        }

        $groups = $this->separatePointGroups();

        // return render
        return $this->render("FTNWBundle:Editor:netmail-list.html.twig", array(
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'messages' => $pm,
            'netmail_unread' => $this->getPointUnreadNetmail(),
            'pager' => $paginator->pageList,
        ));
    }

    public function bookmarkEchomailAction(Request $request) {
        $em = $this->getDoctrine();
        $result = ['switched'=>false];
        if (!$message = $em->getRepository('FTNWBundle:PointMessage')->findOneBy(['area'=>$request->request->get('group_id'),'message'=>$request->request->get('message_number'),'point'=>$this->getUser()->getId()])) {
            return new JsonResponse($result);
        }
        if ($message->getBookmarked()) {
            $message->setBookmarked(false);
            $result['state'] = false;
        } else {
            $message->setBookmarked(true);
            $result['state'] = true;
        }
        $ent = $em->getManager();
        $ent->persist($message);
        $ent->flush();

        $result['switched'] = true;

        return new JsonResponse($result);
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
        $cache = new ApcuCache();
        $cache->delete($this->getUser()->getId().'.areas');
        return $this->redirectToRoute('fidonews_group',['group_id'=>$group_id]);
    }

    public function echomailListAction($group_id, $all=false, Request $request) {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        // Count total records for pagination
        $qb->select($qb->expr()->count('mp'))
            ->from('FTNWBundle:PointMessage', 'mp')
            ->where('mp.area = :area_id')
            ->andWhere('mp.point = :point_id')
            ->setParameter('area_id',$group_id)
            ->setParameter('point_id',$this->getUser()->getId());
        if (!$all) {
            $qb->andWhere("mp.seen = 0");
        }
        
        $totalRecords = $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 1800)
            ->getSingleScalarResult();

        // Init paginator
        $paginator = $this->paginate(
            $totalRecords,
            $pageNumber = $request->get('page'),
            $onPage=100,
            'list'
        );

        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select("m.id,m.hFrom,m.hFromFtn,m.hTo,m.hToFtn,m.subject,m.hDate,p.id as pid,p.seen")
            ->from("FTNWBundle:PointMessage","p")
            ->leftJoin("FTNWBundle:MessageCache","m",'WITH',"m.id=p.message")
            ->where("p.point = :point_id")
            ->andWhere("p.area = :area_id")
            ->orderBy("m.hDate","desc")
            ->setMaxResults($paginator->limit)
            ->setFirstResult($paginator->offset)
            ->setParameter("point_id",$this->getUser()->getId())
            ->setParameter("area_id",$group_id);

        if (!$all) {
            $qb->andWhere("p.seen = 0");
        }
        $pm = $qb->getQuery()->getResult();

        $groups = $this->separatePointGroups();

        return $this->render("FTNWBundle:Editor:echomail-group-list.html.twig", array(
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'group_current' => $this->getDoctrine()->getRepository("FTNWBundle:Echoarea")->find($group_id),
            'netmail_unread' => $this->getPointUnreadNetmail(),
            'messages' => $pm,
            'pager' => $paginator->pageList,
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

        $groups = $this->separatePointGroups();

        return $this->render('FTNWBundle:Editor:netmail-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    public function netmailPostEmailAction(Request $request) {
        if ($this->getParameter('internet_gate') == null) {
            $this->addFlash('notice','This node is not configured to send emails');
            return $this->redirectToRoute('netmail');
        }
        $point = $this->getUser();
        $batch = new Netmail();
        $batch->setBody("\n\n\n".$point->getSubscription()."\n");

        $form = $this->createForm(EmailPostType::class,$batch);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Add data to batch record
            $batch->setPoint($point);
            $batch->setHToFtn($this->getParameter('internet_gate'));
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
                $this->addFlash("notice", "Email sent to ".$batch->getHTo());
                return $this->redirectToRoute("netmail_message", ['netmail_id' => $batch->getId()]);
            }
        }

        $groups = $this->separatePointGroups();

        return $this->render('FTNWBundle:Editor:email-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }
    public function netmailPostAction($nm=null,$rfca=null,Request $request) {
        $point = $this->getUser();

        $batch = new Netmail();
        $batch->setBody("\n\n\n".$point->getSubscription()."\n");
        if ($nm != null) {
            $batch->setHTo($nm);
        }
        if ($rfca != null) {
            $batch->setHToFtn($this->makeFTN($rfca));
        }

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

        $groups = $this->separatePointGroups();

        return $this->render('FTNWBundle:Editor:netmail-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }
    public function netmailcheckAction() {
        if ($this->getUser()->getAslistNetmail()) {
            return $this->redirectToRoute('netmail_list_all');
        }
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
            $groups = $this->separatePointGroups();
            return $this->render("FTNWBundle:Editor:netmail.html.twig", array(
                'groups' => $groups['all'],
                'groups_visible' => $groups['visible'],
                'groups_more' => $groups['more'],
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

        $groups = $this->separatePointGroups();

        // return render
        return $this->render("FTNWBundle:Editor:netmail.html.twig", array(
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
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

        $groups = $this->separatePointGroups();
        return $this->render('FTNWBundle:Editor:echomail-new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'group_current' => $area,
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
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
        $groups = $this->separatePointGroups();

        return $this->render('FTNWBundle:Editor:echomail-reply.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
            'group_current' => $message->getEchoarea(),
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'netmail_unread' => $this->getPointUnreadNetmail(),
        ));
    }

    private function bodyReplier($body,$from) {
        // parse from name
        $nm = explode(" ",$from,2);
        $new_quote = " ";
        foreach ($nm as $name) {
            $new_quote .= mb_substr($name,0,1);
        }
        $new_quote .= "> ";

        $barr = explode("\n",$body);
        $replied = array();
        foreach ($barr as $bline) {
            if ($bline!="") {
                if (preg_match("/([a-zA-Z\ ]+\>{1,})\ (.+)/",$bline,$quoted)) {
                    $replied []= " ".trim($quoted[1])."> ".$quoted[2];
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
        $groups = $this->separatePointGroups();
        $netmail_unread = $this->getPointUnreadNetmail();
        $point = $this->getUser()->getNum();
        $dashboard_list = array();
        // netmail first
        $dashboard_list[0]['cnt'] = $netmail_unread;
        $dashboard_list[0]['desc'] = "Netmail";
        $dashboard_list[0]['link'] = $this->generateUrl('netmail');
        // areas
        /*
        for ($i=1;$i<3;$i++) {
            if (count($groups) > $i-1) {
                $dashboard_list[$i]['cnt'] = $groups['visible'][$i-1]['cnt'];
                $dashboard_list[$i]['desc'] = $groups['visible'][$i-1]['name'];
                $dashboard_list[$i]['link'] = $this->generateUrl('fidonews_group',array('group_id'=>$groups['visible'][$i-1]['id']));
            }
        }*/

        // render template
        return $this->render('FTNWBundle:Editor:groups.html.twig', array(
            'point_num' => $point,
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'netmail_unread' => $netmail_unread,
            'dashboard' => $dashboard_list,
        ));
    }

    public function groupAction($group_id) {
        if ($this->getUser()->getAslistEchomail()) {
            return $this->redirectToRoute('fidonews_group_listall',['group_id'=>$group_id]);
        }

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
            $groups = $this->separatePointGroups();
            return $this->render("FTNWBundle:Editor:groups.html.twig", array(
                'groups' => $groups['all'],
                'groups_visible' => $groups['visible'],
                'groups_more' => $groups['more'],
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

        // check message is read/unread and bookmarked or not
        $pm = $em->getManager()->getRepository('FTNWBundle:PointMessage')
            ->findOneBy(['message'=>$message_number,'area'=>$group_id,'point'=>$this->getUser()->getId()]);
        if (!$pm) {
            $this->addFlash('error',"Requested message not found");
            return $this->redirectToRoute("fidonews_editor");
        }

        // get message data
        $message = $pm->getMessage();

        /*
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
        */

        // check message exists for point
        if (!$message) {
            $this->addFlash('error',"Requested message not found");
            return $this->redirectToRoute("fidonews_editor");
        }

        // create navigation
        $navigation = new Navigation();

        // previous message id
        $nm_qb = $em->getManager()->createQueryBuilder();
        $nm_qb->select('mc.id')
            ->from('FTNWBundle:PointMessage','pm')
            ->leftJoin('FTNWBundle:MessageCache','mc','WITH','mc.id=pm.message')
            ->where('mc.hDate<:mess_date')
            ->andWhere('mc.echoarea=:group_id')
            ->andWhere('pm.point=:point_id')
            ->orderBy('mc.hDate','DESC')
            ->setMaxResults(1)
            ->setParameter('mess_date',$message->getHDate()->format('Y-m-d H:i:s'))
            ->setParameter('group_id',$group_id)
            ->setParameter('point_id',$this->getUser()->getId())
            ->getQuery();
        try {
            $prev = $nm_qb->getQuery()->getSingleScalarResult();
            $navigation->setPrev($prev);
        } catch (NoResultException $e) {
            $navigation->setPrev(false);
        }

        // next message id
        $pm_qb = $em->getManager()->createQueryBuilder();
        $pm_qb->select('mc.id')
            ->from('FTNWBundle:PointMessage','pm')
            ->leftJoin('FTNWBundle:MessageCache','mc','WITH','mc.id=pm.message')
            ->where('mc.hDate>:mess_date')
            ->andWhere('mc.echoarea=:group_id')
            ->andWhere('pm.point=:point_id')
            ->orderBy('mc.hDate','ASC')
            ->setMaxResults(1)
            ->setParameter('mess_date',$message->getHDate()->format('Y-m-d H:i:s'))
            ->setParameter('group_id',$group_id)
            ->setParameter('point_id',$this->getUser()->getId())
            ->getQuery();
        try {
            $next = $pm_qb->getQuery()->getSingleScalarResult();
            $navigation->setNext($next);
        } catch (NoResultException $e) {
            $navigation->setNext(false);
        }

        // reply/new message links
        $navigation->setNew($this->generateUrl('fidonews_post',['id'=>$group_id]));
        $navigation->setReply($this->generateUrl('fidonews_reply',['id'=>$message->getId()]));

        // set message as read
        if (!$pm->getSeen()) {
            $pm->setSeen(true);
            $ent = $em->getManager();
            $ent->persist($pm);
            $ent->flush();
            $this->discountCachedGroup($group_id);
        }

        // groups list
        $groups = $this->separatePointGroups();

        $message->setBody(str_replace(['<','>'],['&lt;','&gt;'],$message->getBody()));

        // Colorize quotes in message body
        $message->setBody($this->colorizeQuotes($message->getBody()));

        // unseen cnt
        foreach($groups['all'] as $group) {
            if ($group['id']==$group_id) {
                $navigation->setUnseen($group['cnt']);
            }
        }

        $attachment = false;
        $att_check = preg_match_all("/begin \d{3,} (.+)([\w\W]+)end/",$message->getBody(),$uue_data,PREG_SET_ORDER);
        if ($att_check && $att_check>0) {
            $num = 0;
            $attachment = [];
            foreach ($uue_data as $data) {
                $attachment[] = [
                    'file' => $data[1],
                    //'content' => convert_uudecode($data[2]),
                    'num' => $num,
                    'mid' => $message->getId(),
                ];
                $num++;
            }
        }
        //if (count($attachment)>0) dump($uue_data);

        if (preg_match("/begin \d{3,} (.+)([\w\W]+)end/",$message->getBody(),$data)) {
            $message->setBody(str_replace($data[2],"\n-UUE/cutted-\n",$message->getBody()));
        }


        // return render
        return $this->render("FTNWBundle:Editor:groups.html.twig", array(
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'group_current' => $em->getRepository("FTNWBundle:Echoarea")->find($group_id),
            'message' => $message,
            'pm' => $pm,
            'nav' => $navigation,
            'netmail_unread' => $this->getPointUnreadNetmail(),
            'attachments' => $attachment,
        ));
    }

    public function bookmarksListAction() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select("m.id,m.hFrom,m.hFromFtn,m.hTo,m.hToFtn,m.subject,m.hDate,p.id as pid,e.name as areaname,e.id as aid")
            ->from("FTNWBundle:PointMessage","p")
            ->leftJoin("FTNWBundle:MessageCache","m",'WITH',"m.id=p.message")
            ->leftJoin("FTNWBundle:Echoarea","e",'WITH',"e.id=p.area")
            ->where("p.point = :point_id")
            ->andWhere("p.bookmarked = 1")
            ->orderBy("m.hDate","desc")
            ->setParameter("point_id",$this->getUser()->getId());
        $pm = $qb->getQuery()->getResult();

        $groups = $this->separatePointGroups();

        return $this->render("FTNWBundle:Editor:bookmarks-list.html.twig", array(
            'groups' => $groups['all'],
            'groups_visible' => $groups['visible'],
            'groups_more' => $groups['more'],
            'netmail_unread' => $this->getPointUnreadNetmail(),
            'messages' => $pm,
        ));
    }
    public function bookmarksOpsAction(Request $request) {
        if ($request->request->get('type')=='markread' && count($request->request->get('unreads'))>0) {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $qb->update("FTNWBundle:PointMessage","p")->set('p.bookmarked',0)->where('p.id IN (:ids)')
                ->setParameter('ids',array_keys($request->request->get('unreads')));
            if ($qb->getQuery()->execute()) {
                $this->addFlash('notice','Selected messages removed from bookmarks');
            } else {
                $this->addFlash('error','Cannot remove selected messages from bookmarks');
            }
        }
        return $this->redirectToRoute('bookmarks_list');
    }

    private function getPointGroups() {
        $point_id = $this->getUser()->getId();
        $cache = new ApcuCache();
        $areacache = $cache->fetch($this->getUser()->getId().'.areas');
        if (!$areacache) {
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
            $cache->save($this->getUser()->getId().'.areas',$grouplist,600);
        } else {
            $grouplist = $areacache;
        }
        return $grouplist;
    }

    private function discountCachedGroup($group_id) {
        $cache = new ApcuCache();
        if ($areacache = $cache->fetch($this->getUser()->getId().'.areas')) {
            $data = array('group'=>$group_id,'user'=>$this->getUser()->getId());
            try {
            array_walk($areacache,function(&$item,$idx,$data){
                if ($item['id']==$data['group']) {
                    if ($item['cnt']>0) {
                        $item['cnt']--;
                    }
                    if ($item['cnt'] == 0) {
                        //$cache = new ApcuCache();
                        //$cache->delete($data['user'].'.areas');
                        throw new \Exception();
                    }
                }
            },$data);
            $cache->save($this->getUser()->getId().'.areas',$areacache,600);

            } catch (\Exception $e) {
              $cache->delete($this->getUser()->getId().'.areas');
            }
        }
    }

    private function separatePointGroups() {
        $grouplist = $this->getPointGroups();
        $groups = ['visible'=>[],'more'=>null];
        if (count($grouplist) > $this->getParameter('group_list_visible')) {
            $groups['more'] = [];
            for ($i=0;$i<count($grouplist);$i++) {
                if ($i < $this->getParameter('group_list_visible')) {
                    $groups['visible'][] = $grouplist[$i];
                } else {
                    $groups['more'][] = $grouplist[$i];
                }
            }
        } else {
            $groups['visible'] = $grouplist;
        }
        $groups['all'] = $grouplist;
        return $groups;
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
            return $data[3].":".$data[2]."/".$data[1];
        } else {
            return false;
        }
    }

    /**
     * @param $totalRecords
     * @param $pageNumber
     * @param $onPage
     * @param string $navType [list/pager/blog]
     * @param bool $useHex [true/false] page number is HEX or DEC
     * @param bool $availZero
     * @param bool $showMax
     * @return \stdClass
     */
    private function paginate ($totalRecords,$pageNumber,$onPage,$navType='list',$useHex=false,$availZero=false,$showMax=false) {
        if ($useHex && ctype_xdigit($pageNumber)) {
            $pageNumber = hexdec($pageNumber);
        }

        if ($availZero) $backStep = 0;
        else $backStep = 1;
        if ($pageNumber<$backStep) throw $this->createNotFoundException('Invalid page number');

        $p = new \stdClass();
        $p->limit = $onPage;
        $p->currentPage = $pageNumber;
        $p->totalPages = ceil($totalRecords/$p->limit);
        if ($p->totalPages == 0) $p->totalPages = 1;
        if ($p->totalPages-1+$backStep<$pageNumber) throw $this->createNotFoundException('Invalid page number');
        $p->offset = ($p->currentPage-$backStep)*$p->limit;
        $p->pageList = new \Doctrine\Common\Collections\ArrayCollection();

        // Default pager [1][2][3][etc..]
        if ($navType == 'list') {
            // Page numbers iteration
            for ($i=$backStep;$i<$p->totalPages+$backStep;$i++) {

                // Page previous
                if ($p->currentPage>$backStep && $i+1==$p->currentPage) {
                    if ($useHex) $linkTo = dechex($p->currentPage-1);
                    else $linkTo = $p->currentPage-1;
                    $p->pageList->add(['link'=>$linkTo,'alt'=>"&larr;&nbsp;".$linkTo,'state'=>'']);
                    // Page current
                } elseif ($p->currentPage == $i) {
                    if ($useHex) $linkTo = dechex($i);
                    else $linkTo = $i;
                    $p->pageList->add(['link'=>$linkTo,'alt'=>$linkTo,'state'=>'active']);
                    // Page next
                } elseif ($p->currentPage+1<$p->totalPages && $i==$p->currentPage+1) {
                    if ($useHex) $linkTo = dechex($p->currentPage+1);
                    else $linkTo = $p->currentPage+1;
                    $p->pageList->add(['link'=>$linkTo,'alt'=>$linkTo.'&nbsp;&rarr;','state'=>'']);
                } else {
                    if ($useHex) $linkTo = dechex($i);
                    else $linkTo = $i;
                    $p->pageList->add(['link'=>$linkTo,'alt'=>$linkTo,'state'=>'']);
                }
            }
            // PageByPage [Previos][Next]
        } elseif ($navType == 'pager') {
            // BlogStyle pager [Older][Newer]
        } elseif ($navType == 'blog') {
            // Go older c:1 -> 2
            if ($p->currentPage+1<$p->totalPages) {
                if ($useHex) $linkTo = dechex($p->currentPage+1);
                else $linkTo = $p->currentPage+1;
                $p->pageList->add(['link'=>$linkTo,'alt'=>'&larr; page.'.$linkTo,'state'=>'']);
            }
            // Show current
            if ($useHex) $linkTo = dechex($p->currentPage);
            else $linkTo = $p->currentPage;
            $p->pageList->add(['link'=>$linkTo,'alt'=>"".$linkTo,'state'=>'disabled']);
            // Go newer c:2 -> 1
            if ($p->currentPage-1>=$backStep) {
                if ($useHex) $linkTo = dechex($p->currentPage-1);
                else $linkTo = $p->currentPage-1;
                $p->pageList->add(['link'=>$linkTo,'alt'=>"page.".$linkTo.' &rarr;','state'=>'']);
            }
        }

        return $p;
    }

}
