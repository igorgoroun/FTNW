<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function uplinkNewAction(Request $request)
    {


        return new JsonResponse([]);
    }

    private function checkauth ($key) {
        $my_key = $this->getParameter('node_api_passwd');
        $my_add = $this->getParameter('node_ftn_address');
        if ($key == md5($my_add.$my_key)) {
            return true;
        } else {
            return false;
        }
    }
}
