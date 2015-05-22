<?php

require __DIR__ . '/../vendor/autoload.php';

// Set image source
$source = new League\Flysystem\Filesystem(
	new League\Flysystem\Adapter\Local(__DIR__ . '/media/')
);

// Set image cache
$cache = new League\Flysystem\Filesystem(
	new League\Flysystem\Adapter\Local(__DIR__ . '/media-cache/')
);

// Set image manager
$imageManager = new Intervention\Image\ImageManager([
	'driver' => 'gd',
]);

// Set manipulators
$manipulators = [
	new League\Glide\Api\Manipulator\Size(2048*2048),
	new League\Glide\Api\Manipulator\Sharpen(),
	new League\Glide\Api\Manipulator\Output(),
];

// Set API
$api = new League\Glide\Api\Api($imageManager, $manipulators);

// Setup Glide server
$server = new League\Glide\Server($source, $cache, $api);
$server->setBaseUrl('/media/');


$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$variant = $request->query->getAlnum('variant', 'thumb');
$variants = [
	'thumb' => [
		'fm' => 'jpg',
		'q' => '30', // Must be string https://github.com/thephpleague/glide/issues/59
		'fit' => 'crop',
		'w' => '500',
		'h' => '375',
		'sharp' => '10',
	],
	'thumbRect' => [
		'fm' => 'jpg',
		'q' => '30', // Must be string https://github.com/thephpleague/glide/issues/59
		'fit' => 'crop',
		'w' => '300',
		'h' => '300',
		'sharp' => '10',
	],
	'detail' => [
		'fm' => 'jpg',
		'q' => '50', // Must be string https://github.com/thephpleague/glide/issues/59
		'fit' => 'crop',
		'w' => '1600',
		'h' => '1200',
	],
];

if (!isset($variants[$variant]))
{
	$response = new \Symfony\Component\HttpFoundation\Response('', 400);
	$response->send();
	die();
}

$request->query->add($variants[$variant]);
$server->outputImage($request);
