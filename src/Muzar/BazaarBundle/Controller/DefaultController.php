<?php

namespace Muzar\BazaarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MuzarBazaarBundle:Default:index.html.twig', array());
    }
}
