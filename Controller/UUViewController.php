<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

class UUViewController extends Controller
{
    public function echoMessageAction($mid,$num)
    {
        $em = $this->getDoctrine();

        // check message is read/unread and bookmarked or not
        $pm = $em->getManager()->getRepository('FTNWBundle:PointMessage')
            ->findOneBy(['message'=>$mid,'point'=>$this->getUser()->getId()]);

        // get message data
        $message = $pm->getMessage();

        $att_check = preg_match_all("/begin \d{3,} (.+)([\w\W]+)end\n/",$message->getBody(),$uue_data,PREG_SET_ORDER);

        if ($att_check && $att_check>0) {
            $headers = array(
                'Content-Type'     => 'application/x-www-form-urlencoded',
                'Content-Disposition' => 'inline; filename="'.$uue_data[0][1].'"'
            );
            //$file = new BinaryFileResponse($uue_data[0][2]);

            $response = new Response(convert_uudecode("begin 644 ".trim($uue_data[0][1]).$uue_data[0][2]."end\n"), 200, $headers);
            //$file = new BinaryFileResponse()
            return $response;

        }

    }
}
