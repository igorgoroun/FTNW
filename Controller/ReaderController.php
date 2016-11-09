<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Doctrine\Common\Cache\ApcuCache;
use IgorGoroun\FTNWBundle\Entity\MessageCache;
use IgorGoroun\FTNWBundle\Entity\Echoarea;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReaderController extends Controller
{
    public function messageListAction(Echoarea $area, Request $request) {
        $db = $this->getDoctrine()->getManager();
        $qb = $db->createQueryBuilder();
        // Count total records for pagination
        $totalRecords = $qb->select($qb->expr()->count('m'))
            ->from('FTNWBundle:MessageCache', 'm')
            ->where('m.echoarea = :area_id')
            ->setParameter('area_id',$area->getId())
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 1800)
            ->getSingleScalarResult();

        // Init paginator
        $paginator = $this->paginate(
            $totalRecords,
            $pageNumber = $request->get('page'),
            $onPage=10,
            'blog'
        );

        // messages list with paging
        $messages_rep = $this->getDoctrine()->getRepository('FTNWBundle:MessageCache');
        $messages = $messages_rep->findBy(['echoarea'=>$area->getId()],['hDate'=>'DESC'],$paginator->limit,$paginator->offset);

        return $this->render("FTNWBundle:Reader:area-messages.html.twig", array(
            'area' => $area,
            'messages' => $messages,
            'pager' => $paginator->pageList,
        ));
    }

    public function areasListAction() {
        $em = $this->getDoctrine();
        // list of available echoareas
        $cache = new ApcuCache();
        $areacache = $cache->fetch('reader.areas');
        //$areacache=false;
        if (!$areacache) {
            $qb = $em->getManager()->createQueryBuilder();
            $qb->select(["e.name","e.id","COUNT(m.id) as cnt","MAX(m.hDate) as lastdate"])
                ->from("FTNWBundle:Echoarea",'e')
                ->leftJoin("FTNWBundle:MessageCache",'m','WITH','m.echoarea=e.id')
                ->groupBy("e.id")
                ->addOrderBy('e.name','ASC')
                ->setLifetime(1200);
            $grouplist = $qb->setCacheable(true)->getQuery()->getResult();
            $cache->save('reader.areas',$grouplist,1200);
        } else {
            $grouplist = $areacache;
        }

        // render template
        return $this->render('FTNWBundle:Reader:area-list.html.twig', array(
            'groups' => $grouplist,
        ));
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
