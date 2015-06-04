<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
		$bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

			new Muzar\ScraperBundle\MuzarScraperBundle(),
            new Muzar\ProductCatalogBundle\MuzarProductCatalogBundle(),
            new Muzar\BazaarBundle\MuzarBazaarBundle(),
			new JMS\SerializerBundle\JMSSerializerBundle(),
			new FOS\RestBundle\FOSRestBundle(),
			new FOS\ElasticaBundle\FOSElasticaBundle(),
			new FOS\UserBundle\FOSUserBundle(),
			new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
			new Nelmio\CorsBundle\NelmioCorsBundle(),
			new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        );

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'/config/'.$this->getEnvironment().'/config.yml');
	}

	public function getCacheDir() {
		if (in_array($this->getEnvironment(), array('docker')))
		{
			return sprintf('/dev/shm/%s/cache/%s', $this->getName(),  $this->getEnvironment());
		}
		return parent::getCacheDir();
	}

	public function getLogDir() {
		if (in_array($this->getEnvironment(), array('docker')))
		{
			return sprintf('/dev/shm/%s/logs/%s', $this->getName(),  $this->getEnvironment());
		}
		return parent::getLogDir();
	}
}
