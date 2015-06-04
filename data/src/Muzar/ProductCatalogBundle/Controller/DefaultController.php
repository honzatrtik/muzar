<?php

namespace Muzar\ProductCatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MuzarProductCatalogBundle:Default:index.html.twig', array('name' => $name));
    }
}
