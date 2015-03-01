<?php

namespace Muzar\BazaarBundle\Tests\Entity;


use Elastica\Client;
use Muzar\BazaarBundle\Suggestion\ElasticaQuerySuggester;
use Muzar\BazaarBundle\Suggestion\QuerySuggesterInterface;
use Muzar\BazaarBundle\Tests\ApiTestCase;


class ElasticaQuerySuggesterTest extends ApiTestCase
{

	/** @var  QuerySuggesterInterface */
	protected $suggester;

	protected function setUp()
	{
		parent::setUp();
		$this->runCommand('fos:elastica:reset', array(
			'--no-debug' => TRUE,
			'--index' => 'website',
			'--type' => 'query',
			'--force' => true,
		));
		$this->suggester = new ElasticaQuerySuggester(
			$this->getClient(),
			'website',
			'query'
		);
	}

	/**
	 * @return Client
	 */
	protected function getClient()
	{
		return $this->getKernel()->getContainer()->get('fos_elastica.client.default');
	}

	public function testGet()
	{
		$this->suggester->add('GODIN LA Patrie Concert');
		$this->suggester->add('Washburn RX 25 FVSB');
		$this->suggester->add('Epiphone DOT');
		$this->suggester->add('EPIPHONE FAT-210', 10);
		$this->suggester->add('Epiphone Les Junior');

		$this->getClient()->refreshAll();

		$suggestions = $this->suggester->get('epi');

		$this->assertInternalType('array', $suggestions);
		$this->assertCount(3, $suggestions);

	}

	public function testGet2()
	{
		$this->suggester->add('GODIN LA Patrie Concert');
		$this->suggester->add('Washburn RX 25 FVSB');
		$this->suggester->add('Epiphone DOT');
		$this->suggester->add('EPIPHONE FAT-210', 10);
		$this->suggester->add('Epiphone Les Junior');

		$this->getClient()->refreshAll();

		$suggestions = $this->suggester->get('conc');

		$this->assertInternalType('array', $suggestions);
		$this->assertCount(1, $suggestions);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testAddEmpty()
	{
		$this->suggester->add('');
	}





}
