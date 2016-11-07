<?php

namespace IgorGoroun\FTNWBundle\Controller;

use Doctrine\Common\Cache\ApcuCache;
use IgorGoroun\FTNWBundle\Entity\reCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use IgorGoroun\FTNWBundle\Entity\Point;
use IgorGoroun\FTNWBundle\Form\SettingsType;

class PointController extends Controller
{

    public function settingsAction(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(SettingsType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice','Ваши данные сохранены.');

            return $this->redirectToRoute('point_settings');
        }
        return $this->render(
            'FTNWBundle:Point:settings.html.twig',
            array(
                'form' => $form->createView(),
                'point_num' => $user->getNum(),
            )
        );
    }

    public function registerAction(Request $request)
    {
        $user = new Point();
        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->reCaptchaValid($request)) {
            $password = $this->get('security.password_encoder')->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);
            $user->setActive(false);
            $user->setNum($this->genFreeNum());
            $user->setFtnaddr($this->genFTNAddr($user->getNum()));
            $user->setIfaddr($this->genIfAddr($user->getFtnaddr()));
            $user->setIfname($this->genIfName($user->getUsername()));
            $user->setOrigin($this->getParameter('point_default_origin'));
            $user->setSubscription($this->getParameter('point_default_subscription').$user->getUsername().', '.$user->getFtnaddr());
            $user->setCreated(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice','Ваша учетная запись будет активирована через несколько минут.');

            if ($user->getClassic()) {
                return $this->redirectToRoute('point_classic_info',['num'=>$user->getNum()]);
                /*
                return $this->render('FTNWBundle:Point:classic.html.twig',
                    array(
                        'point' => $user,
                    )
                );*/
            } else {
                return $this->redirectToRoute('point_web_info',['num'=>$user->getNum()]);
                /*
                return $this->render('FTNWBundle:Point:confirmweb.html.twig',
                    array(
                        'point' => $user,
                    )
                );*/
            }
        }

        return $this->render(
            'FTNWBundle:Point:registration.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
    private function reCaptchaValid(Request $request) {
        $cap = $request->request->get('g-recaptcha-response');
        $re = new reCaptcha($cap,$secret=$this->getParameter('recaptcha_secret_key'),$url=$this->getParameter('recaptcha_verify_host'));
        return $re->verifyResponse();
    }
    private function genIfName($name) {
        return mb_strtolower(preg_replace('/\ /','_',$name));
    }
    private function genIfAddr($ftn) {
        preg_match('/(\d{1})\:(\d{1,4})\/(\d{1,4})\.(\d{1,4})/',$ftn,$data);
        $ifaddr = 'p'.$data[4].'.f'.$data[3].'.n'.$data[2].'.z'.$data[1].'.fidonet.org';
        return $ifaddr;
    }
    private function genFTNAddr($num) {
        $node = $this->getParameter('node_ftn_address');
        $point = $num;
        return $node.'.'.$point;
    }
    private function genFreeNum() {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select($qb->expr()->max('p.num'))
            ->from('FTNWBundle:Point','p');
        $max_num = $qb->getQuery()->getSingleScalarResult();
        if (is_null($max_num)) {
            return 1;
        } else {
            return $max_num+1;
        }

    }

    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            'FTNWBundle:Point:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'ftnw_ver' => $this->getParameter('ftnw_ver'),
            )
        );
    }

    public function logoutAction() {
        return $this->redirectToRoute('point_login');
    }

    public function classicAction($num) {
        return $this->render('FTNWBundle:Point:classic.html.twig',array('num'=>$num));
    }
    public function webbsAction($num) {
        return $this->render('FTNWBundle:Point:confirmweb.html.twig',array('num'=>$num));
    }
    public function faqAction() {
        return $this->render('FTNWBundle:Point:faq.html.twig');
    }

}
