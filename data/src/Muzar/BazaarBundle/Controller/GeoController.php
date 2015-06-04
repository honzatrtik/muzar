<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet;
use FOS\RestBundle\Controller\Annotations as Rest;
use Muzar\BazaarBundle\Entity\Category;
use Muzar\BazaarBundle\Entity\CategoryService;
use Muzar\BazaarBundle\Entity\GeoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="muzar_bazaar.controller.geo")
 */
class GeoController
{

	/** @var  Router */
	protected $router;

	/** @var  GeoService */
	protected $geoService;

	public function __construct(Router $router, GeoService $categoryService)
	{
		$this->router = $router;
		$this->geoService = $categoryService;
	}

	/**
	 * @Route("/geo/regions", name="muzar_bazaar_geo_all")
	 * @Rest\View
	 */
	public function regionsAction(Request $request)
    {
		return array(
			'meta' => array(),
			'data' => $this->geoService->getRegionsAndDistricts(),
		);
    }

}
