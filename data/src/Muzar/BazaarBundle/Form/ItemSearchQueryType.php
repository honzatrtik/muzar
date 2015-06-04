<?php
/**
 * Date: 03/03/14
 * Time: 14:05
 */

namespace Muzar\BazaarBundle\Form;

use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemSearchQueryType extends AbstractType
{

	/**
	 * @var Category[]
	 */
	protected $categories;

	/** @var boolean */
	protected $crsf;

	function __construct($crsf = FALSE)
	{
		$this->crsf = $crsf;
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('query', 'text', array(
				'required'  => false
			))
			->add('priceFrom', 'integer', array(
				'required'  => false
			))
			->add('priceTo', 'integer', array(
				'required'  => false
			))
			->add('save', 'submit');

	}


	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Muzar\BazaarBundle\Entity\ItemSearchQuery',
			'csrf_protection'  => $this->crsf,
			'cascade_validation' => TRUE,
		));
	}

	public function getName()
	{
		return 'item_search_query';
	}

} 