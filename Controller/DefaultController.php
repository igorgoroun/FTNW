<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FTNWBundle:Default:index.html.twig');
    }
}
