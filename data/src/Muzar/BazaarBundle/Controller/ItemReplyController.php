<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use DoctrineExtensions\NestedSet;
use FOS\RestBundle\View\View;
use Muzar\BazaarBundle\Constraints\ItemReplyConstraint;
use Muzar\BazaarBundle\Entity\ItemService;
use Muzar\BazaarBundle\MailerService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Constraints;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route(service="muzar_bazaar.controller.item_reply")
 */
class ItemReplyController
{

	/** @var  Router */
	protected $router;

	/** @var  ItemService */
	protected $itemService;

	/** @var  EntityManager */
	protected $em;

	/** @var  RecursiveValidator */
	protected $validator;

	/** @var  MailerService */
	protected $mailerService;

	public function __construct(
		Router $router,
		RecursiveValidator $validator,
		ItemService $itemService,
		MailerService $mailerService
	)
	{
		$this->router = $router;
		$this->validator = $validator;
		$this->itemService = $itemService;
		$this->mailerService = $mailerService;
	}


	/**
	 * @Route("/ads/{id}/replies", name="muzar_bazaar_item_reply", requirements={"id" = "\d+"})
	 * @Method("POST")
	 * @Rest\View()
	 */
	public function postAction(Request $request, $id)
	{
		$item = $this->itemService->getItem($id);

		$data  = $request->request;

		$violations = $this->validator->validate($data->all(), $this->getConstraint());
		if (count($violations))
		{
			return View::create(array(
				'errors' => $violations,
			), Response::HTTP_BAD_REQUEST);
		}

		$message = $this->mailerService->createItemReplyMessage(
			$item,
			$data->get('email'),
			$data->get('message'),
			$data->get('name'),
			$data->get('phone')
		);

		$this->mailerService->send($message);

		return new Response('', Response::HTTP_ACCEPTED);
	}

	protected function getConstraint()
	{
		$fields = array(
			'email' => new Constraints\Email(),
			'name'  => new Constraints\NotBlank(),
			'phone'  => new Constraints\NotBlank(),
			'message'  => new Constraints\NotBlank(),
		);

		return new Constraints\Collection(array(
			'fields' => $fields,
		));
	}

}

