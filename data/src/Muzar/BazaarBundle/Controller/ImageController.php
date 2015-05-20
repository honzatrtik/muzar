<?php

namespace Muzar\BazaarBundle\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\NestedSet;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Flysystem\FilesystemInterface;
use Muzar\BazaarBundle\Filesystem\GarbageCollectorInterface;
use Muzar\BazaarBundle\Filesystem\UrlMapperInterface;
use Muzar\BazaarBundle\Media\ItemMediaInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route(service="muzar_bazaar.controller.image")
 */
class ImageController
{

	const MAX_PIXELS = 16777216; // 4096 * 4096

	/** @var  FilesystemInterface */
	private $fs;

	/** @var  GarbageCollectorInterface */
	private $gc;

	/** @var  UrlMapperInterface */
	private $urlMapper;

	public function __construct(FilesystemInterface $fs, UrlMapperInterface $urlMapper, GarbageCollectorInterface $gc)
	{
		$this->fs = $fs;
		$this->gc = $gc;
		$this->urlMapper = $urlMapper;
	}

	/**
	 * @Route("/images", name="muzar_bazaar_image")
	 * @Method("POST")
	 * @Rest\View
	 */
	public function postAction(Request $request)
    {
		/** @var UploadedFile $file */
		$file = $request->files->get('file');

		if (!$file->isValid())
		{
			throw new HttpException(400);
		}

		// Check image
		$pathname = $file->getPathname();
		if (!$file->isReadable() || !$file->isFile())
		{
			throw new \RuntimeException('File not readable.');
		}

		$this->validateImage($pathname);
		$this->runGarbageCollector();

		$stream = fopen($pathname, 'r');
		$id = join('/', str_split(substr(md5(uniqid()), 0, 4), 2))
			. '/' . md5($file->getBasename()) . '.' . $file->getExtension();

		$this->fs->putStream($id, $stream);
		fclose($stream);

		unlink($pathname);

		return array(
			'meta' => array(),
			'data' => array(
				'id' => $id,
				'image_url' => $this->urlMapper->map($id),
			),
		);
    }

	/**
	 * @Route("/images/{id}", name="muzar_bazaar_image_get", requirements={"id"=".+"})
	 * @Method("GET")
	 * @Rest\View
	 */
	public function getAction(Request $request, $id)
	{

		if (!$this->fs->has($id))
		{
			throw new HttpException(404);
		}

		return array(
			'meta' => array(),
			'data' => array(
				'id' => $id,
				'image_url' => $this->urlMapper->map($id),
			),
		);
	}

	protected function validateImage($pathname)
	{
		if (!$data = getimagesize($pathname))
		{
			throw new HttpException(400, sprintf('Not image "%s".', $pathname));
		}

		if ($data[0] * $data[1] > self::MAX_PIXELS)
		{
			throw new HttpException(400, sprintf('Image too big "%s".', $pathname));
		}
	}

	protected function runGarbageCollector()
	{
		$this->gc->run($this->fs, '');
	}

}
